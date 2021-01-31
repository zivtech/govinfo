<?php

namespace Drupal\govinfo\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'other_identifier' widget.
 *
 * @FieldWidget(
 *   id = "other_identifier_widget",
 *   label = @Translation("Other Identifier"),
 *   field_types = {
 *     "other_identifier"
 *   }
 * )
 */

class OtherIdentifierWidgetType extends WidgetBase {

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

    $element['name'] = [
      '#type' => 'textfield',
      '#title' => 'Name',
      '#default_value' => isset($items[$delta]->name) ? $items[$delta]->name : NULL,
      '#size' => 40,
      '#maxlength' => 40,
    ];
    $element['value'] = [
      '#type' => 'textfield',
      '#title' => 'Value',
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#size' => 10,
      '#maxlength' => 10,
    ];

    // If cardinality is 1, ensure a label is output for the field by wrapping
    // it in a details element.
    if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == 1) {
      $element += [
        '#type' => 'fieldset',
        '#attributes' => array('class' => array('container-inline')),
      ];
    }
    else {
      $element += [
        '#type' => 'fieldset',
        '#title' => t('Other Identifier'),
        '#attributes' => array('class' => array('container-inline')),
      ];
      $element['#title_display'] = 'visible';
    }

    return $element;
  }

}
