<?php

namespace Drupal\govinfo\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Tweet entity entities.
 */
class GranuleEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
