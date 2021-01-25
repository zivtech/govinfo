<?php

/**
 * The package summary entity for govinfo.
 */

namespace Drupal\govinfo\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Language\LanguageInterface;
use Drupal\user\UserInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Defines the govinfo Summary entity.
 *
 * @ingroup govinfo
 *
 * @ContentEntityType(
 *   id = "govinfo_summary",
 *   label = @Translation("govinfo Summaries"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\govinfo\SummaryEntityListBuilder",
 *     "views_data" = "Drupal\govinfo\Entity\SummaryEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\govinfo\Form\SummaryEntityForm",
 *       "add" = "Drupal\govinfo\Form\SummaryEntityForm",
 *       "edit" = "Drupal\govinfo\Form\SummaryEntityForm",
 *       "delete" = "Drupal\govinfo\Form\SummaryEntityDeleteForm",
 *     },
 *     "access" = "Drupal\govinfo\SummaryEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\govinfo\SummaryEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "govinfo_summary",
 *   admin_permission = "administer govinfo summary entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "packageId",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/govinfo_summary/{summary_entity}",
 *     "add-form" = "/admin/structure/govinfo_summary/add",
 *     "edit-form" = "/admin/structure/govinfo_summary/{summary_entity}/edit",
 *     "delete-form" = "/admin/structure/govinfo_summary/{summary_entity}/delete",
 *     "collection" = "/admin/structure/govinfo_summary",
 *   },
 *   field_ui_base_route = "govinfo_summary.settings"
 * )
 */
class SummaryEntity extends ContentEntityBase implements SummaryEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->get('id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getUuid($uuid) {
    return $this->get('uuid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setUuid($uuid) {
    $this->set('uuid', $uuid);
    return $this;
  }

  public function getPackageId() {
    return $this->get('package_id')->value;
  }


  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Standard field, used as unique if primary index.
    $fields['sid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Summary ID'))
      ->setDescription(t('The Summary entry id.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the summary entity.'))
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was changed.'));

    $field['last_modified'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Last Modified'))
      ->setDescription(t('The last time the summary was modified in govinfo.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $field['date_issued'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Date Issued'))
      ->setDescription(t('The time the summary entry was issued on govinfo.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', [
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 15,
      ])
      ->setDisplayOptions('form', [
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['collection_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Collection Code'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 20,
      ])
      ->setDisplayOptions('form', [
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['collection_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Collection Name'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 25,
      ])
      ->setDisplayOptions('form', [
        'weight' => 25,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['category'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Category'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 30,
      ])
      ->setDisplayOptions('form', [
        'weight' => 30,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['details_link'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Details Link URL'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 30,
      ])
      ->setDisplayOptions('form', [
        'weight' => 30,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['granules_link'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Granules Link URL'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 35,
      ])
      ->setDisplayOptions('form', [
        'weight' => 35,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['package_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Package ID'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 40,
      ])
      ->setDisplayOptions('form', [
        'weight' => 40,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // $fields['hashtags'] = BaseFieldDefinition::create('entity_reference')
    //   ->setLabel(t('Hashtags Used'))
    //   ->setDescription(t('Any hashtags that are contained in a tweet.'))
    //   ->setRevisionable(FALSE)
    //   ->setSetting('target_type', 'taxonomy_term')
    //   ->setSetting('handler', 'default:taxonomy_term')
    //   ->setSetting('handler_settings', [
    //     'target_bundles' => [
    //       'twitter_hashtag_terms' => 'twitter_hashtag_terms',
    //     ],
    //   ])
    //   ->setTranslatable(FALSE)
    //   ->setDisplayOptions('view', [
    //     'weight' => 9,
    //   ])
    //   ->setDisplayOptions('form', [
    //     'type' => 'entity_reference_autocomplete',
    //     'weight' => 9,
    //     'settings' => [
    //       'match_operator' => 'CONTAINS',
    //       'size' => '60',
    //       'autocomplete_type' => 'tags',
    //       'placeholder' => '',
    //     ],
    //   ])
    //   ->setCardinality(-1)
    //   ->setDisplayConfigurable('form', TRUE)
    //   ->setDisplayConfigurable('view', TRUE);

    $fields['download'] = BaseFieldDefinition::create('summary_downloads_field_type')
      ->setLabel(t('Downloads'))
      ->setDescription(t('Users mentioned in the tweet'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 45,
      ])
      ->setDisplayOptions('form', [
        'type' => 'summary_downloads_widget',
        'weight' => 45,
      ])
      ->setCardinality(1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // $fields['user_mentions_tags'] = BaseFieldDefinition::create('entity_reference')
    //   ->setLabel(t('User Mentions'))
    //   ->setDescription(t('The users mentioned in a tweet in the form of tags.'))
    //   ->setSetting('target_type', 'taxonomy_term')
    //   ->setSetting('handler', 'default:taxonomy_term')
    //   ->setSetting('handler_settings', [
    //     'target_bundles' => [
    //       'twitter_user_mention_terms' => 'twitter_user_mention_terms',
    //     ],
    //   ])
    //   ->setTranslatable(FALSE)
    //   ->setDisplayOptions('view', [
    //     'weight' => 17,
    //   ])
    //   ->setDisplayOptions('form', [
    //     'type' => 'entity_reference_autocomplete',
    //     'weight' => 17,
    //     'settings' => [
    //       'match_operator' => 'CONTAINS',
    //       'size' => '60',
    //       'autocomplete_type' => 'tags',
    //       'placeholder' => '',
    //     ],
    //   ])
    //   ->setCardinality(-1)
    //   ->setDisplayConfigurable('form', TRUE)
    //   ->setDisplayConfigurable('view', TRUE);


    // $fields['profile_image'] = BaseFieldDefinition::create('image')
    //   ->setLabel(t('Profile Image'))
    //   ->setDescription(t('The Profile Image of the Tweeter.'))
    //   ->setSettings([
    //     'uri_scheme' => 'public',
    //     'file_directory' => 'tweet-feed-tweet-profile-images/[date:custom:Y]-[date:custom:m]',
    //     'alt_field_required' => FALSE,
    //     'file_extensions' => 'png jpg jpeg gif',
    //   ])
    //   ->setDisplayOptions('view', array(
    //     'label' => 'hidden',
    //     'type' => 'default',
    //     'weight' => 22,
    //   ))
    //   ->setDisplayOptions('form', array(
    //     'weight' => 22,
    //   ))
    //   ->setCardinality(1)
    //   ->setDisplayConfigurable('form', TRUE)
    //   ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
