<?php

namespace Drupal\govinfo\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'committees' widget.
 *
 * @FieldWidget(
 *   id = "committees_widget",
 *   label = @Translation("Committees"),
 *   field_types = {
 *     "committees"
 *   }
 * )
 */
class CommitteesWidgetType extends WidgetBase {

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

    $element['authority_id'] = [
      '#type' => 'textfield',
      '#title' => 'Authority ID',
      '#default_value' => isset($items[$delta]->authority_id) ? $items[$delta]->authority_id : NULL,
      '#size' => 64,
      '#maxlength' => 64,
    ];
    $element['chamber'] = [
      '#type' => 'textfield',
      '#title' => 'Chamber',
      '#default_value' => isset($items[$delta]->chamber) ? $items[$delta]->chamber : NULL,
      '#size' => 32,
      '#maxlength' => 8,
    ];
    $element['type'] = [
      '#type' => 'textfield',
      '#title' => 'Type',
      '#default_value' => isset($items[$delta]->type) ? $items[$delta]->type : NULL,
      '#size' => 32,
      '#maxlength' => 8,
    ];
    $element['committee_name'] = [
      '#type' => 'textfield',
      '#title' => 'Committee Name',
      '#default_value' => isset($items[$delta]->committee_name) ? $items[$delta]->committee_name : NULL,
      '#size' => 64,
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
