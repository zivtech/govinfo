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
 * Plugin implementation of the 'parties' field type.
 *
 * @FieldType(
 *   id = "parties",
 *   label = @Translation("Parties"),
 *   default_widget = "parties_widget",
 *   default_formatter = "parties_formatter"
 * )
 */
class PartiesFieldType extends FieldItemBase {

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

    $properties['last_name'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Last Name'))
      ->setRequired(FALSE);

    $properties['middle_name'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Middle Name'))
      ->setRequired(FALSE);
      
    $properties['first_name'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('First Name'))
      ->setRequired(FALSE);

    $properties['role'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Role'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'last_name' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
        'middle_name' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
        'first_name' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
        'role' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
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
    foreach (['last_name', 'middle_name', 'first_name', 'role'] as $variable) {
      $x = $this->get($variable)->getValue();
      if (!empty($x) && $x != 0) {
        $empty = FALSE;
        break;
      }
    }
    return $empty;
  }
}
