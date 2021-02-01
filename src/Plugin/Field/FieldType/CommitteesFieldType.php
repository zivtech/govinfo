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
 * Plugin implementation of the 'committees' field type.
 *
 * @FieldType(
 *   id = "committees",
 *   label = @Translation("Comittees"),
 *   default_widget = "committees_widget",
 *   default_formatter = "committees_formatter"
 * )
 */
class CommitteesFieldType extends FieldItemBase {

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

    $properties['authority_id'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Authority Id'))
      ->setRequired(FALSE);

    $properties['chamber'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Chamber'))
      ->setRequired(FALSE);

    $properties['type'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Type'))
      ->setRequired(FALSE);

    $properties['committee_name'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Committee Name'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'authority_id' => [
          'type' => 'varchar',
          'length' => 64,
          'not null' => TRUE,
        ],
        'chamber' => [
          'type' => 'varchar',
          'length' => 32,
          'not null' => TRUE,
        ],
        'type' => [
          'type' => 'varchar',
          'length' => 32,
          'not null' => TRUE,
        ],
        'committee_name' => [
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
    foreach (['authority_id', 'chamber', 'type', 'committee_name'] as $variable) {
      $x = $this->get($variable)->getValue();
      if (!empty($x) && $x != 0) {
        $empty = FALSE;
        break;
      }
    }
    return $empty;
  }
}
