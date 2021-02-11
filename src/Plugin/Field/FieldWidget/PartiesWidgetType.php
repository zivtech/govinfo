<?php

namespace Drupal\govinfo\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'parties' widget.
 *
 * @FieldWidget(
 *   id = "parties_widget",
 *   label = @Translation("Parties"),
 *   field_types = {
 *     "parties"
 *   }
 * )
 */
class PartiesWidgetType extends WidgetBase {

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

    $element['last_name'] = [
      '#type' => 'textfield',
      '#title' => 'Last Name',
      '#default_value' => isset($items[$delta]->last_name) ? $items[$delta]->last_name : NULL,
      '#size' => 64,
      '#maxlength' => 64,
    ];
    $element['middle_name'] = [
      '#type' => 'textfield',
      '#title' => 'Middle Name',
      '#default_value' => isset($items[$delta]->middle_name) ? $items[$delta]->middle_name : NULL,
      '#size' => 64,
      '#maxlength' => 64,
    ];
    $element['first_name'] = [
      '#type' => 'textfield',
      '#title' => 'First Name',
      '#default_value' => isset($items[$delta]->first_name) ? $items[$delta]->first_name : NULL,
      '#size' => 64,
      '#maxlength' => 64,
    ];
    $element['role'] = [
      '#type' => 'textfield',
      '#title' => 'Role',
      '#default_value' => isset($items[$delta]->role) ? $items[$delta]->role : NULL,
      '#size' => 32,
      '#maxlength' => 32,
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
