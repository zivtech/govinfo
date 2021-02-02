<?php

namespace Drupal\govinfo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Messenger;

use GovInfo\Api;
use GovInfo\Collection;
use GovInfo\Requestor\CollectionAbstractRequestor;

use Drupal\govinfo\Entity\SummaryEntity;

/**
 * Class govinfoCollectionsForm.
 */
class govinfoCollectionsForm extends ConfigFormBase {

  private $db;
  private $api;
  private $message;
  private $collection;
  private $collectionAbstractRequestor;

  /**
   * Load our usable objects into scope.
   */
  public function __construct() {
    $config = $this->config('govinfo.settings');
    $api_key = ($config->get('api_key') != NULL) ? $config->get('api_key') : NULL;

    $this->db = \Drupal::database();
    $this->message = \Drupal::messenger();

    if (!empty($api_key)) {
      $this->api = new Api(new \GuzzleHttp\Client(), $api_key);
      $this->collection = new Collection($this->api);
      $this->collectionAbstractRequestor = new CollectionAbstractRequestor();
    }
    else {
      // If we have not configured our API key, we need to go to that page and
      // let them know they need to configure it
      
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'govinfo.collections',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'govinfo_collections_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('govinfo.collections');
    $enabled = $config->get('enabled_codes');
    $enabled = (!empty($enabled)) ? (array_combine($enabled, $enabled)) : [];

    if (empty($this->api)) {
      $this->message->addWarning(
        t('You have not provided a govinfo API key. Please provide your govinfo key in the space below and') . ' ' .
        t('then come back to this collections page.'));
      $this->message->addWarning(
        t('If you need an API key, go to: https://api.data.gov/signup'));
      $this->message->addWarning(
        t('If you have a key already, navigate to configuration > web services > govinfo > settings to provide it.'));
    }
    else {
      // Now that we have our key, attempt to pull the collections and store them
      // so that we can display them on the refreshed screen and in other interface
      // locations.
      
      $collection = new Collection($this->api);
      $collection_index = $collection->index();
      $collections = $collection_index['collections'];

      foreach ($collections as $collection) {
        $this->db->merge('govinfo_collections')
          ->key('code', $collection['collectionCode'])
          ->fields([
            'name' => $collection['collectionName'],
            'package_count' => (int) $collection['packageCount'],
            'granule_count' => (int) $collection['granuleCount'],
          ])
          ->execute();
      }

      // Check to see if we've loaded our collections. If we have, allow them to be selected.
      $collection = $this->db->select('govinfo_collections', 'gc');
      $collection_count = $collection
        ->countQuery()
        ->execute()
        ->fetchField();

      if ($collection_count > 0) {
        $result = $collection
          ->fields('gc', ['code', 'name', 'package_count', 'granule_count'])
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
          '#empty' => $this->t('THERE ARE NO govinfo RECORDS AVAILABLE.'),
          '#default_value' => $enabled,
        ];
      }
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

      $this->config('govinfo.collections')
        ->set('enabled_codes', $enabled)
        ->save();

      /**
       * For each enabled item, we need to call an item to get the count. In this,
       * we generate the URL's to be used in our queue. This is better for two reasons:
       *
       * 1. We don't have to crawl all the URL's to calculate the URL's
       * 2. We don't want to wait for all the crawling to happen right now.
       * 3. We only have a limited number of api calls an hour, so we need
       *    to meter how much calling we are doing.
       */
      $code = [];
      foreach ($enabled as $collection_code) {
        $start_date = new \DateTime('1994-01-01T20:18:10Z');
        $this->collectionAbstractRequestor->setStrCollectionCode($collection_code);
        $this->collectionAbstractRequestor->setObjStartDate($start_date);
        $currentOffset = 0;

        // Clear the database of any items in the current collection code.
        $this->db->delete('govinfo_collection_meta')
          ->condition('doc_class', $collection_code)
          ->execute();

        do {
          $this->collectionAbstractRequestor->setIntPageSize(100);
          $this->collectionAbstractRequestor->setIntOffSet($currentOffset);
          $item = $this->collection->item($this->collectionAbstractRequestor);
          foreach ($item['packages'] as $package) {
            $this->db->insert('govinfo_collection_meta')
              ->fields([
                'collection_code' => $collection_code,
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
      }
    }
  }
}
