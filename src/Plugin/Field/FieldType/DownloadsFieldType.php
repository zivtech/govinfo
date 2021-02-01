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
 * Plugin implementation of the 'download_field_type' field type.
 *
 * @FieldType(
 *   id = "downloads",
 *   label = @Translation("Downloads"),
 *   description = @Translation("Downloads for a collection summary."),
 *   default_widget = "downloads_widget",
 *   default_formatter = "downloads_formatter"
 * )
 */
class DownloadsFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'max_length' => 255,
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties = [];

    $properties['pdf_link'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('PDF Link'))
      ->setRequired(FALSE);

    $properties['xml_link'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('XML Link'))
      ->setRequired(FALSE);

    $properties['htm_link'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('HTML Link'))
      ->setRequired(FALSE);

    $properties['xls_link'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('XLS Link'))
      ->setRequired(FALSE);

    $properties['mods_link'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Mods Link'))
      ->setRequired(FALSE);

    $properties['premis_link'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Premis Link'))
      ->setRequired(FALSE);

    $properties['zip_link'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('ZIP Link'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'pdf_link' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
          'default' => '',
        ],
        'xml_link' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
          'default' => '',
        ],
        'htm_link' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
          'default' => '',
        ],
        'xls_link' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
          'default' => '',
        ],
        'mods_link' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
          'default' => '',
        ],
        'premis_link' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
          'default' => '',
        ],
        'zip_link' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
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

    if ($max_length = $this->getSetting('max_length')) {
      $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();

      foreach (['pdf', 'xml', 'htm', 'xls', 'mods', 'premis', 'zip'] as $prefix) {
        $constraints[] = $constraint_manager->create('ComplexData', [
          $prefix . '_link' => [
            'Length' => [
              'max' => $max_length,
              'maxMessage' => t('%name: may not be longer than @max characters.', [
                '%name' => $this->getFieldDefinition()->getLabel(),
                '@max' => $max_length
              ]),
            ],
          ],
        ]);
      }
    }

    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $elements = [];

    $elements['max_length'] = [
      '#type' => 'number',
      '#title' => t('Maximum length'),
      '#default_value' => $this->getSetting('max_length'),
      '#required' => TRUE,
      '#description' => t('The maximum length of the field in characters.'),
      '#min' => 1,
      '#disabled' => $has_data,
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $empty = TRUE;
    foreach (['pdf', 'xml', 'htm', 'xls', 'mods', 'premis', 'zip'] as $prefix) {
      $x = $this->get($prefix . '_link')->getValue();
      if (!empty($x)) {
        $empty = FALSE;
        break;
      }
    }
    return $empty;
  }

}
