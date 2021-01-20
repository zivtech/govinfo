<?php

namespace Drupal\govinfo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use GovInfo\Api;
use GovInfo\Collection;

/**
 * Class govinfoSettingsForm.
 */
class govinfoSettingsForm extends ConfigFormBase {

  private $db;

  public function __construct() {
    $this->db = \Drupal::database();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'govinfo.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'govinfo_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('govinfo.settings');
    $api_key = ($config->get('api_key') != NULL) ? $config->get('api_key') : NULL;

    $data_gov_link = Link::fromTextAndUrl($this->t('here'), Url::fromUri('https://api.data.gov/signup'));
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('govinfo API Key'),
      '#description' => $this->t('The API key provided by data.gov. If you need an API key, signup ') . $data_gov_link->toString() . '.',
      '#maxlength' => 48,
      '#size' => 48,
      '#default_value' => $api_key,
      '#required' => TRUE,
      '#weight' => 10,
    ];
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
    $this->config('govinfo.settings')
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();
    
    // Now that we have our key, attempt to pull the collections and store them
    // so that we can display them on the refreshed screen.
    $api = new Api(
      new \GuzzleHttp\Client(), 
      $form_state->getValue('api_key')
    );
    $collection = new Collection($api);
    $collection_index = $collection->index();
    $collections = $collection_index['collections'];

    foreach ($collections as $collection) {
      $this->db->insert('govinfo_collections')
      ->fields([
        'code' => $collection['collectionCode'],
        'name' => $collection['collectionName'],
        'package_count' => (int) $collection['packageCount'],
        'granule_count' => (int) $collection['granuleCount'],
      ])
      ->execute();
    }
  }
}
