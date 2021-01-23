<?php

namespace Drupal\govinfo\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Tweet entity entities.
 *
 * @ingroup govinfo
 */
interface SummaryEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Get the package ID as it is on govinfo.
   *
   * @return string $packageId
   *   The Package ID of the package summary object.
   */
  public function getPackageId();

}
