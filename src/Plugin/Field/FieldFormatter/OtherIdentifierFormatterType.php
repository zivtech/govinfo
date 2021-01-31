<?php

namespace Drupal\govinfo\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'other_identifier_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "other_identifier_formatter",
 *   label = @Translation("Other Identifier"),
 *   field_types = {
 *     "other_identifier"
 *   }
 * )
 */
class OtherIdentifierFormatterType extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => $this->viewValue($item)];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    $values = $item->toArray();


    // $display = NULL;
    // foreach ($values as $key => $value) {
    //   if (!empty($value)) {
    //     $display .= '<div class="summary-document-link"><a class="summary-document-anchor" href="/summary-download/' . base64_encode($value) . '">' . $labels[$key] . '</div>';
    //   }
    // }
    // return $display;
    return NULL;
  }

}
