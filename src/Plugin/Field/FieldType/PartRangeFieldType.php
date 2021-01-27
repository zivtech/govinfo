<?php

namespace Drupal\govinfo\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'part_range' field type.
 *
 * @FieldType(
 *   id = "part_range",
 *   label = @Translation("Part Range"),
 *   default_widget = "part_range_widget",
 *   default_formatter = "part_range_formatter"
 * )
 */
class PartRangeFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties = [];

    $properties['from'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('From'))
      ->setRequired(FALSE);

    $properties['to'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('To'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'from' => [
          'type' => 'int',
          'not null' => TRUE,
          'default' => '0',
        ],
        'to' => [
          'type' => 'int',
          'not null' => TRUE,
          'default' => '0',
        ],
      ],
    ];
    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $elements = [];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $empty = TRUE;
    foreach (['from', 'to'] as $variable) {
      $x = $this->get($variable)->getValue();
      if (!empty($x) && $x != 0) {
        $empty = FALSE;
        break;
      }
    }
    return $empty;
  }
}
