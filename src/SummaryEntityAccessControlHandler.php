<?php

namespace Drupal\govinfo;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Tweet entity entity.
 *
 * @see \Drupal\tweet_feed\Entity\TweetEntity.
 */
class SummaryEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\govinfo\Entity\SummaryEntityInterface $entity */
    switch ($operation) {
      case 'view':
      $entity->getHashtags();
      if (!$entity->isQuotedOrRepliedTweet()) {
        return AccessResult::allowedIfHasPermission($account, 'view base quoted or replied-to tweets.');
      }
      return AccessResult::allowedIfHasPermission($account, 'access content');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit tweet entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete tweet entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add tweet entities');
  }

}
