<?php

namespace Drupal\govinfo;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Summary Entities.
 *
 * @ingroup govinfo
 */
class SummaryEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['sid'] = $this->t('Summary Id');
    $header['package_id'] = $this->t('Package Id');
    $header['title'] = $this->t('Title and Category');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\tweet_feed\Entity\TweetEntity */
    $row['sid'] = $entity->getSummaryId();
    $row['package_id'] = $entity->getPackageId();
    $row['title'] = Link::createFromRoute(
      $entity->getTitle() . ': '. $entity->getCategory(),
      'entity.summary_entity.edit_form'
    );
    return $row + parent::buildRow($entity);
  }

}
