<?php

namespace Drupal\govinfo\Commands;

use Drush\Commands\DrushCommands;
use Drupal\govinfo\Controller\SummaryEntity;
use Drupal\Core\Messenger;

use GovInfo\Api;
use GovInfo\Collection;
use GovInfo\Package;
use GovInfo\Requestor\CollectionAbstractRequestor;
use GovInfo\Requestor\PackageAbstractRequestor;

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

  protected $packageAbstractRequestor;

  /**
   * Load our usable objects into scope.
   */
  public function __construct() {
    $config = \Drupal::service('config.factory')->get('govinfo.settings');
    $api_key = ($config->get('api_key') != NULL) ? $config->get('api_key') : NULL;

    $this->db = \Drupal::database();
    $this->message = \Drupal::messenger();
    $this->api = (!empty($api_key)) ? new Api(new \GuzzleHttp\Client(), $api_key) : NULL;
    $this->collection = new Collection($this->api);
    $this->collectionAbstractRequestor = new CollectionAbstractRequestor();
    $this->packageAbstractRequestor = new PackageAbstractRequestor();
  }

  /**
   * Index the latest entries for each of the selected collections.
   *
   * @usage govinfo:index
   *   Import data based on enabled code and last imported dates
   *
   * @command govinfo:index
   * @aliases gix
   */
  public function index() {
    $collections_config = \Drupal::service('config.factory')->get('govinfo.collections');
    $codes = $collections_config->get('enabled_codes');

    foreach ($codes as $code) {

      /**
       * Get the last indexed date per code so we can append our entries to end of the
       * metadata table for indexing.
       */
      $result = $this->db->select('govinfo_collections', 'gc')
        ->fields('gc', ['last_index'])
        ->condition('gc.code', $code, '=')
        ->execute();
      $last_index = $result->fetchField();

      $index_date = new \DateTime(date('Y-m-d', $last_index) . 'T' . date('H:i:s', $last_index) . 'Z');
      $this->collectionAbstractRequestor->setStrCollectionCode($code);
      $this->collectionAbstractRequestor->setObjStartDate($index_date);
      $currentOffset = 0;

      do {
        $this->collectionAbstractRequestor->setIntPageSize(100);
        $this->collectionAbstractRequestor->setIntOffSet($currentOffset);
        $item = $this->collection->item($this->collectionAbstractRequestor);

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
        ->fields(['last_index' => $time])
        ->condition('code', $code, '=')
        ->execute();
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

  }
}
