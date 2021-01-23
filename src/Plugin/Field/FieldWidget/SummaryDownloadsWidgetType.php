<?php

namespace Drupal\govinfo\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'summary_downloads' widget.
 *
 * @FieldWidget(
 *   id = "summary_downloads_widget",
 *   label = @Translation("Summary Downloads"),
 *   field_types = {
 *     "summary_downloads_field_type"
 *   }
 * )
 */
class SummaryDownloadsWidgetType extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = t('Textfield size: @size', ['@size' => $this->getSetting('size')]);
    if (!empty($this->getSetting('placeholder'))) {
      $summary[] = t('Placeholder: @placeholder', ['@placeholder' => $this->getSetting('placeholder')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['pdf_link'] = [
      '#type' => 'textfield',
      '#title' => 'PDF Link',
      '#default_value' => isset($items[$delta]->pdf_link) ? $items[$delta]->pdf_link : NULL,
      '#size' => 120,
      '#maxlength' => 255,
    ];
    $element['xml_link'] = [
      '#type' => 'textfield',
      '#title' => 'XML Link',
      '#default_value' => isset($items[$delta]->xml_link) ? $items[$delta]->xml_link : NULL,
      '#size' => 120,
      '#maxlength' => 255,
    ];
    $element['htm_link'] = [
      '#type' => 'textfield',
      '#title' => 'HTML Link',
      '#default_value' => isset($items[$delta]->htm_link) ? $items[$delta]->htm_link : NULL,
      '#size' => 120,
      '#maxlength' => 255,
    ];
    $element['xls_link'] = [
      '#type' => 'textfield',
      '#title' => 'XLS Link',
      '#default_value' => isset($items[$delta]->xls_link) ? $items[$delta]->xls_link : NULL,
      '#size' => 120,
      '#maxlength' => 255,
    ];
    $element['mods_link'] = [
      '#type' => 'textfield',
      '#title' => 'Mods Link',
      '#default_value' => isset($items[$delta]->mods_link) ? $items[$delta]->mods_link : NULL,
      '#size' => 120,
      '#maxlength' => 255,
    ];
    $element['premis_link'] = [
      '#type' => 'textfield',
      '#title' => 'Premis Link',
      '#default_value' => isset($items[$delta]->premis_link) ? $items[$delta]->premis_link : NULL,
      '#size' => 120,
      '#maxlength' => 255,
    ];
    $element['zip_link'] = [
      '#type' => 'textfield',
      '#title' => 'Zip Link',
      '#default_value' => isset($items[$delta]->zip_link) ? $items[$delta]->zip_link : NULL,
      '#size' => 120,
      '#maxlength' => 255,
    ];
    
    // If cardinality is 1, ensure a label is output for the field by wrapping
    // it in a details element.
    if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == 1) {
      $element += [
        '#type' => 'fieldset',
        
      ];
    }
    return $element;
  }

}
