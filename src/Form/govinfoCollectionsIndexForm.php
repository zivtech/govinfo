<?php

namespace Drupal\govinfo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Messenger;

use GovInfo\Api;
use GovInfo\Collection;
use GovInfo\Package;
use GovInfo\Requestor\CollectionAbstractRequestor;
use GovInfo\Requestor\PackageAbstractRequestor;

use Drupal\govinfo\Entity\SummaryEntity;

/**
 * Class govinfoCollectionsIndexForm.
 */
class govinfoCollectionsIndexForm extends ConfigFormBase {

  private $db;
  private $api;
  private $message;
  private $collectionRequestor;
  private $packageRequestor;

  /**
   * Load our usable objects into scope.
   */
  public function __construct() {
    $config = $this->config('govinfo.settings');
    $api_key = ($config->get('api_key') != NULL) ? $config->get('api_key') : NULL;

    $this->db = \Drupal::database();
    $this->message = \Drupal::messenger();
    $this->api = (!empty($api_key)) ? new Api(new \GuzzleHttp\Client(), $api_key) : NULL;
    $this->collectionRequestor = new CollectionAbstractRequestor();
    $this->packageRequestor = new PackageAbstractRequestor();
  }

  /**
   * {@inheritdoc}
   */
   protected function getEditableConfigNames() {
     return [
       'govinfo.collections_index',
     ];
   }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'govinfo_collections_index_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('govinfo.collections');
    $enabled = $config->get('enabled_codes');
    
    if (empty($this->api)) {
      $this->message->addWarning(
        t('You have not provided a govinfo API key. Please provide your govinfo key in the space below and') . ' ' .
        t('then come back to this collections index page.'));
      $this->message->addWarning(
        t('If you need an API key, go to: https://api.data.gov/signup'));
      $this->message->addWarning(
        t('If you have a key already, navigate to configuration > web services > govinfo > settings to provide it.'));
    }
    elseif (empty($enabled)) {
      $this->message->addWarning(
        t('You have not selected any collection codes for indexing. Please go to the collections list and') . ' ' .
        t('select codes to be indexed.'));
    }
    else {

      // Display the list of curtaed collections so we can select which ones we want to index.
      $collection = $this->db->select('govinfo_collections', 'gc');

      $result = $collection
        ->fields('gc', ['code', 'name', 'package_count', 'granule_count'])
        ->condition('code', $enabled, 'IN')
        ->execute();

      $header = [
        'code' => $this->t('Code'),
        'name' => $this->t('Name'),
        'package_count' => $this->t('Package Count'),
        'granule_count' => $this->t('Granule Count'),
      ];

      $options = [];
      foreach ($result as $record) {
        $options[$record->code] = [
          'code' => $record->code,
          'name' => $record->name,
          'package_count' => $record->package_count,
          'granule_count' => $record->granule_count,
        ];
      }

      $form['options'] = [
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $options,
        '#empty' => $this->t(''),
        '#default_value' => $enabled,
      ];
      return parent::buildForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);    
    $options = $form_state->getValue('options');
    if (!empty($options)) {
      $enabled = [];
      foreach ($options as $key=>$value) {
        if ($key == $value) {
          $enabled[] = $value;
        }
      }

      if (!empty($enabled)) {

        $collection = new Collection($this->api);

        // Get batches by pages of metadata and not all of the individual items
        // This will reduce our timeout possibilities.
        // process the batch as pages of 100 entries. So for every 1 item in the batch,
        // we're doing 100 of whatever... collections, packages, granules, etc.
        foreach ($enabled as $code) {
          $this->collectionRequestor->setStrCollectionCode($code);
          $startDate = new \DateTime('2020-01-01T20:18:10Z');
          $this->collectionRequestor->setObjStartDate($startDate);
          $this->collectionRequestor->setIntOffset(0);

          do {
            $response = $collection->item($this->collectionRequestor);
            $this->processPackages($response['packages']);
          } while ($response['nextPage']);
          $this->processPackages($response['packages']);
        }
      }
    }
  }

  private function processPackages(array $packages) {
    $pack = new Package($this->api);
    foreach ($packages as $key => $package) {
      if ($key < 1) {
        continue;
      }
      $this->packageRequestor->setStrPackageId($package['packageId']);
      $sdata = $pack->summary($this->packageRequestor);

      $summary = new SummaryEntity();
      $summary->setOwnerId(1);
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
      $summary->setPages($sdata['pages']);
      $summary->setGovernmentAuthor($sdata['govermentAuthor1'], $sdata['governmentAuthor2']);
      $summary->setSuDocClassNumber($sdata['suDocClassNumber']);
      $summary->setDocumentType($sdata['documentType']);
      
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
      $summary->setOtherIdentifiers($sdata['otherIdentifier']);
      $summary->setLastModified(strtotime($sdata['lastModified']));

      $summary->save();


      print "<pre>";
      print_r($sdata);
      exit();


      $granules = $this->getGranules($package['packageId']);
    }
  }

  private function getGranules(string $packageId) {
    $pack = new Package($this->api);
    $granules = $pack->granules($this->packageRequestor);
    foreach ($granules['granules'] as $granule) {

      $this->packageRequestor->setStrGranuleId($granule['granuleId']);
      $granuleSummary = $pack->granuleSummary($this->packageRequestor);
      print "<pre>";
      print_r($granuleSummary);
      exit();

    }



  }

}
