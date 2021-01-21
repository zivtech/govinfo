<?php

namespace Drupal\govinfo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use GovInfo\Api;
use GovInfo\Collection;
use GovInfo\Requestor\CollectionAbstractRequestor;

/**
 * Class govinfoCollectionsIndexForm.
 */
class govinfoCollectionsIndexForm extends ConfigFormBase {

  private $db;
  private $api;
  private $message;
  private $requestor;

  /**
   * Load our usable objects into scope.
   */
  public function __construct() {
    $config = $this->config('govinfo.settings');
    $api_key = ($config->get('api_key') != NULL) ? $config->get('api_key') : NULL;

    $this->db = \Drupal::database();
    $this->message =  \Drupal::messenger();
    $this->api = (!empty($api_key)) ? new Api(new \GuzzleHttp\Client(), $api_key) : NULL;
    $this->requestor = new CollectionAbstractRequestor();
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
    
    if (empty($enabled)) {

    }
    else if (empty($this->api)) {
      $data_gov_link = Link::fromTextAndUrl($this->t('here'), Url::fromUri('https://api.data.gov/signup'));
      $this->message->addMessage(
        t('You have not provided a govinfo API key. Please provide your govinfo key in the space below and') . ' ' .
        t('then come back to this collections page. If you need an API key, click ') . $data_gov_link);
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
        '#empty' => $this->t('THERE ARE NO govinfo RECORDS SELECTED FOR INDEXING.'),
        '#default_value' => $enabled,
      ];
    }
 
    return parent::buildForm($form, $form_state);
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

        // Get batches by pages of metadata and not all of the individual items
        // This will reduce our timeout possibilities.
        // process the batch as pages of 100 entries. So for every 1 item in the batch,
        // we're doing 100 of whatever... collections, packages, granules, etc.




      }
    }
  }
}