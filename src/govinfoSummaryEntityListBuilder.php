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
class govinfoSummaryEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Summary Id');
    $header['title'] = $this->t('Title');
    $header['package_id'] = $this->t('Package Id');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\tweet_feed\Entity\TweetEntity */
    $row['id'] = $entity->id();
    $row['title'] = Link::createFromRoute(
      $entity->getTitle(),
      'entity.govinfo_summary.edit_form',
      ['govinfo_summary' => $entity->id()]
    );
    $row['package_id'] = $entity->getPackageId();
    return $row + parent::buildRow($entity);
  }

}
