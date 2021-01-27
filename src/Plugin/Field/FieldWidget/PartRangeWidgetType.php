<?php

namespace Drupal\govinfo\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'part_range' widget.
 *
 * @FieldWidget(
 *   id = "part_range_widget",
 *   label = @Translation("Part Range"),
 *   field_types = {
 *     "part_range"
 *   }
 * )
 */
class PartRangeWidgetType extends WidgetBase {

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
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['from'] = [
      '#type' => 'textfield',
      '#title' => 'From',
      '#default_value' => isset($items[$delta]->from) ? $items[$delta]->from : NULL,
      '#size' => 10,
      '#maxlength' => 8,
    ];
    $element['to'] = [
      '#type' => 'textfield',
      '#title' => 'To',
      '#default_value' => isset($items[$delta]->to) ? $items[$delta]->to : NULL,
      '#size' => 10,
      '#maxlength' => 8,
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
