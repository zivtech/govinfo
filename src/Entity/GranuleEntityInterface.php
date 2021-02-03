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
interface GranuleEntityInterface extends ContentEntityInterface, EntityOwnerInterface {

}
