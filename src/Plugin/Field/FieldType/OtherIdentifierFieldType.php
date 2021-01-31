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
 * Plugin implementation of the 'other_identifier' field type.
 *
 * @FieldType(
 *   id = "other_identifier",
 *   label = @Translation("Other Identifier"),
 *   default_widget = "other_identifier_widget",
 *   default_formatter = "other_identifier_formatter"
 * )
 */
class OtherIdentifierFieldType extends FieldItemBase {

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

    $properties['name'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Name'))
      ->setRequired(FALSE);

    $properties['value'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Value'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'name' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
          'default' => '',
        ],
        'value' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
          'default' => '',
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
    foreach (['name', 'value'] as $variable) {
      $x = $this->get($variable)->getValue();
      if (!empty($x)) {
        $empty = FALSE;
        break;
      }
    }
    return $empty;
  }
}
