<?php

namespace Drupal\govinfo\Commands;

use Drush\Commands\DrushCommands;
use Drupal\govinfo\Entity\SummaryEntity;
use Drupal\Core\Messenger;

use GovInfo\Api;
use GovInfo\Collection;
use GovInfo\Package;
use GovInfo\Published;
use GovInfo\Requestor\CollectionAbstractRequestor;
use GovInfo\Requestor\PackageAbstractRequestor;
use GovInfo\Requestor\PublishedAbstractRequestor;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class govinfoCommands extends DrushCommands {

  protected $db;

  protected $api;

  protected $message;

  protected $collection;

  protected $collectionAbstractRequestor;

  protected $package;

  protected $packageAbstractRequestor;

  protected $published;
  
  protected $publishedAbstractor;


  /**
   * Load our usable objects into scope.
   */
  public function __construct() {
    $config = \Drupal::service('config.factory')->get('govinfo.settings');
    $api_key = ($config->get('api_key') != NULL) ? $config->get('api_key') : NULL;

    if (!empty($api_key)) {
      $this->db = \Drupal::database();
      $this->message = \Drupal::messenger();
      $this->api = (!empty($api_key)) ? new Api(new \GuzzleHttp\Client(), $api_key) : NULL;
      $this->collection = new Collection($this->api);
      $this->package = new Package($this->api);
      $this->published = new Published($this->api);
      $this->collectionAbstractRequestor = new CollectionAbstractRequestor();
      $this->packageAbstractRequestor = new PackageAbstractRequestor();
      $this->publishedAbstractRequestor = new PublishedAbstractRequestor();
    }
  }

  /**
   * Validate the date from our input
   * https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
   */
  private function validateDate($date, $format = 'Y-m-d') {
    $d = \DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of 
    // digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
  }

  /**
   * Index the latest entries for each of the selected collections.
   *
   * @param $start
   *   The start date (yyyy-mm-dd) for indexing.
   * @param $end
   *   The end date (yyyy-mm-dd) for indexing.
   * @usage govinfo:index
   *   Import data based on enabled code and last imported dates
   *
   * @command govinfo:index
   * @aliases gix
   */
  public function index($start, $end) {
    $collections_config = \Drupal::service('config.factory')->get('govinfo.collections');
    $codes = $collections_config->get('enabled_codes');

    if (!$this->validateDate($start)) {
      $this->logger()->error("govinfo: Illegal start date. Must be in the formay yyyy-mm-dd");
      return;
    }

    if (!$this->validateDate($end)) {
      $this->logger()->error("govinfo: Illegal end date. Must be in the formay yyyy-mm-dd");
      return;
    }

    if (!empty($codes)) {
      foreach ($codes as $code) {
        $this->logger()->notice(dt('Retrieving @code for @start through @end.', [
          '@code' => $code,
          '@start' => $start,
          '@end' => $end
        ]));
        
        $this->publishedAbstractRequestor->setStrCollectionCode($code);
        $this->publishedAbstractRequestor->setStrStartDate($start);
        $this->publishedAbstractRequestor->setStrEndDate($end);
        $currentOffset = 0;

        do {
          $this->publishedAbstractRequestor->setIntPageSize(100);
          $this->publishedAbstractRequestor->setIntOffSet($currentOffset);
          $item = $this->published->item($this->publishedAbstractRequestor);

          if ($item['count'] > 10000) {
            $this->logger()->error(dt('Retrieved record set > 10,000 records. Please narrow the API criteria for finding results.'));
            return;
          }

          if ($item['count'] < 100) {
            $this->logger->notice(dt('Retrieving @records records.', ['@records' => $items['count']]));
          }
          else {
            $this->logger->notice(dt('Retrieving @count of @records records.', 
              ['@count' => $currentOffset, '@records' => $item['count']]));
          }
          
          foreach ($item['packages'] as $package) {
            $this->db->insert('govinfo_collection_meta')
              ->fields([
                'collection_code' => $code,
                'package_id' => $package['packageId'],
                'last_modified' => strtotime($package['lastModified']),
                'package_link' => $package['packageLink'],
                'doc_class' => $package['docClass'],
                'title' => $package['title'],
                'congress' => ($package['congress']) ? $package['congress'] : '',
                'date_issued' => strtotime($package['dateIssued']),
              ])
              ->execute();
          }
          $currentOffset += 100;
        }
        while ($item['nextPage'] != NULL);

        $time = time();
        $result = $this->db->update('govinfo_collections')
          ->fields(['last_indexed' => $time])
          ->condition('code', $code, '=')
          ->execute();
      }
    }
  }

  /**
   * Import data based on what is in the metadata queue. Be sure to respect
   * API limits.
   *
   * @usage govinfo:import
   *   Import data based on enabled code and last imported dates
   *
   * @command govinfo:import
   * @aliases gim
   */
  public function import() {
    $pack = new Package($this->api);
    $result = $this->db->select('govinfo_collection_meta', 'cm')
      ->fields('cm')
      ->orderBy('cid')
      ->execute();
    foreach ($result as $cdata) {
      $this->packageAbstractRequestor->setStrPackageId($cdata->package_id);
      $sdata = $pack->summary($this->packageAbstractRequestor);

      $uuid_service = \Drupal::service('uuid');
      $uuid = $uuid_service->generate();

      $summary = new SummaryEntity([], 'govinfo_summary');
      $summary->setUuid($uuid);
      $summary->setOwnerId(1);
      $summary->setCreatedTime(time());
      $summary->setChangedTime(time());
      $summary->setTitle($sdata['title']);
      $summary->setCollectionCode($sdata['collectionCode']);
      $summary->setCollectionName($sdata['collectionName']);
      $summary->setCategory($sdata['category']);
      $summary->setDateIssued(strtotime($sdata['dateIssued']));
      $summary->setDetailsLink($sdata['detailsLink']);
      $summary->setGranulesLink($sdata['granulesLink']);
      $summary->setPackageId($sdata['packageId']);
      $summary->setDownloads($sdata['download']);
      $summary->setBranch($sdata['branch']);

      if (empty($sdata['governmentAuthor1'])) {
        $sdata['governmentAuthor1'] = '';
      }

      if (empty($sdata['governmentAuthor2'])) {
        $sdata['governmentAuthor2'] = '';
      }

      $summary->setGovernmentAuthor($sdata['governmentAuthor1'], $sdata['governmentAuthor2']);

      if (!empty($sdata['suDocClassNumber'])) {
        $summary->setSuDocClassNumber($sdata['suDocClassNumber']);
      }
     
      $summary->setDocumentType($sdata['documentType']);
      
      if (!empty($sdata['pages'])) {
        $summary->setPages($sdata['pages']);
      }

      if (!empty($sdata['committees'])) {
        $summary->setCommittees($sdata['committees']);
      }

      if (!empty($sdata['congress'])) {
        $summary->setCongress($sdata['congress']);
      }

      if (!empty($sdata['session'])) {
        $summary->setSession($sdata['session']);
      }

      if (!empty($sdata['volume'])) {
        $summary->setVolume($sdata['volume']);
      }

      $summary->setPublisher($sdata['publisher']);

      if (!empty($sdata['otherIdentifier'])) {
        $summary->setOtherIdentifiers($sdata['otherIdentifier']);
      }

      $summary->setLastModified(strtotime($sdata['lastModified']));
      $summary->save();

      $this->getGranules($sdata['packageId']);

      $this->db->delete('govinfo_collection_meta')
        ->condition('package_id', $cdata->package_id, '=')
        ->condition('last_modified', $cdata->last_modified, '=')
        ->execute();
    }
  }

  /**
   * Delete everthing (remove before prod)
   *
   * @usage govinfo:kill
   *   Kill the data with fire.
   *
   * @command govinfo:kill
   * @aliases gik
   */
  public function kill() {
    $this->db->truncate('govinfo_collection_meta')->execute();
    $this->db->truncate('govinfo_granules_meta')->execute();
    $this->db->truncate('govinfo_summary')->execute();
    $this->db->truncate('govinfo_summary__committees')->execute();
    $this->db->truncate('govinfo_summary__government_author')->execute();
    $this->db->truncate('govinfo_summary__other_identifiers')->execute();
    $result = $this->db->update('govinfo_collections')
      ->fields([
        'last_indexed' => 0
      ])->execute();
  }

  private function getGranules($package_id) {
    // Create an index of granules to get
    $pack = new Package($this->api);
    $currentOffset = 0;
    do {
      $this->packageAbstractRequestor->setIntPageSize(100);
      $this->packageAbstractRequestor->setIntOffSet($currentOffset);
      $this->packageAbstractRequestor->setStrPackageId($package_id);
      $granules = $pack->granules($this->packageAbstractRequestor);

      if (!empty($granules['granules'])) {
        foreach ($granules['granules'] as $granule) {
          $this->db->insert('govinfo_granules_meta')
            ->fields([
              'package_id' => $package_id,
              'title' => $granule['title'],
              'granule_id' => $granule['granuleId'],
              'granule_link' => $granule['granuleLink'],
              'granule_class' => $granule['granuleClass'],
            ])
            ->execute();
        }
      }
      $currentOffset += 100;
    } while ($granules['nextPage'] != NULL);

    // $this->packageAbstractRequestor->setStrPackageId($cdata->package_id);
    // $sdata = $pack->summary($this->packageAbstractRequestor);
    //   foreach ($granules['granules'] as $granule) {
    //     $this->packageAbstractRequestor->setStrGranuleId($granule['granuleId']);
    //     $granuleSummary = $pack->granuleSummary($this->packageAbstractRequestor);
    //     print "<pre>";
    //     print_r($granuleSummary);
    //     exit();
    //   }
  }
}
