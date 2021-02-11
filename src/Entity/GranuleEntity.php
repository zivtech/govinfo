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
 * Defines the govinfo Granule entity.
 *
 * @ingroup govinfo
 *
 * @ContentEntityType(
 *   id = "govinfo_granule",
 *   label = @Translation("govinfo Granule"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\govinfo\Entity\govinfoGranuleEntityListBuilder",
 *     "views_data" = "Drupal\govinfo\Entity\GranuleEntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\govinfo\Form\govinfoGranuleEntityForm",
 *       "add" = "Drupal\govinfo\Form\govinfoGranuleEntityForm",
 *       "edit" = "Drupal\govinfo\Form\govinfoGranuleEntityForm",
 *       "delete" = "Drupal\govinfo\Form\govinfoGranuleEntityDeleteForm",
 *     },
 *     "access" = "Drupal\govinfo\Entity\govinfoGranuleEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\govinfo\Entity\govinfoGranuleEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "govinfo_granule",
 *   admin_permission = "administer govinfo granule entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/govinfo_granule/{govinfo_granule}",
 *     "add-form" = "/admin/structure/govinfo_granule/add",
 *     "edit-form" = "/admin/structure/govinfo_granule/{govinfo_granule}/edit",
 *     "delete-form" = "/admin/structure/govinfo_granule/{govinfo_granule}/delete",
 *     "collection" = "/admin/structure/govinfo_granule"
 *   },
 *   field_ui_base_route = "govinfo_granule.settings"
 * )
 */
class GranuleEntity extends ContentEntityBase implements GranuleEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
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
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
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
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
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
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($timestamp) {
    $this->set('changed', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUuid() {
    return $this->get('uuid')->value;
  }

  public function setUuid($uuid) {
    $this->set('uuid', $uuid);
  }

  public function setLastModified($timestamp) {
    $this->set('last_modified', $timestamp);
    return $this;
  }

  public function getLastModified() {
    return $this->get('last_modified')->value;
  }

  public function setDateIssued($timestamp) {
    $this->set('date_issued', $timestamp);
    return $this;
  }

  public function getDateIssued() {
    return $this->get('date_issued')->value;
  }

  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  public function getTitle() {
    return $this->get('title')->value;
  }

  public function setCollectionCode($collection_code) {
    $this->set('collection_code', $collection_code);
    return $this;
  }

  public function getCollectionCode() {
    return $this->get('collectionCode')->value;
  }

  public function setCollectionName($collection_name) {
    $this->set('collection_name', $collection_name);
    return $this;
  }

  public function getCollectionName() {
    return $this->get('collection_name')->value;
  }

  public function setCategory($category) {
    $this->set('category', $category);
    return $this;
  }

  public function getCategory() {
    return $this->get('category')->value;
  }

  public function setDetailsLink($url) {
    $this->set('details_link', $url);
    return $this;
  }

  public function getDetailsLink() {
    return $this->get('details_link');
  }

  public function setGranulesLink($url) {
    $this->set('granules_link', $url);
    return $this;
  }

  public function getGranulesLink() {
    return $this->get('granules_link');
  }

  public function setPackageId($package_id) {
    $this->set('package_id', $package_id);
    return $this;
  }

  public function getPackageId() {
    return $this->get('package_id')->value;
  }

  public function setPackageLink($package_link) {
    $this->set('package_link', $package_link);
    return $this;
  }

  public function getPackageLink() {
    return $this->get('package_link')->value;
  }
  
  public function setDownloads($download) {
    if (!empty($download)) {
      $downloads = [];
      $downloads[] = [
        'pdf_link' => (!empty($download['pdfLink'])) ? $download['pdfLink'] : NULL,
        'xml_link' => (!empty($download['xmlLink'])) ? $download['xmlLink'] : NULL,
        'htm_link' => (!empty($download['htmLink'])) ? $download['htmLink'] : NULL,
        'xls_link' => (!empty($download['xlsLink'])) ? $download['xlsLink'] : NULL,
        'mods_link' => (!empty($download['modsLink'])) ? $download['modsLink'] : NULL,
        'premis_link' => (!empty($download['premisLink'])) ? $download['premisLink'] : NULL,
        'uslm_link' => (!empty($download['uslmLink'])) ? $download['uslmLink'] : NULL,
        'zip_link' => (!empty($download['zipLink'])) ? $download['zipLink'] : NULL,
      ];
      $this->set('downloads', $downloads);
    }
    return $this;
  }

  public function getDownloads() {
    return $this->get('downloads')->value;
  }

  public function setGranuleClass($granule_class) {
    $this->set('granule_class', $granule_class);
    return $this;
  }

  public function getGranuleClass() {
    return $this->get('granule_class')->value;
  }

  public function setSubGranuleClass($sub_granule_class) {
    $this->set('sub_granule_class', $sub_granule_class);
    return $this;
  }

  public function getSubGranuleClass() {
    return $this->get('sub_granule_class')->value;
  }

  public function setHeading($heading) {
    $this->set('heading', $heading);
    return $this;
  }

  public function getHeading() {
    return $this->get('heading')->value;
  }

  public function setGranuleNumber($granule_number) {
    $this->set('granule_number', $granule_number);
    return $this;
  }

  public function getGranuleNumber() {
    return $this->get('granule_number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the summary entity.'))
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    // Standard field, unique outside of the scope of the current project.
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last changed.'));

    // Standard field, unique outside of the scope of the current project.
    $fields['user_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('User Id'))
      ->setDescription(t('The user id of the owner of this tweet.'))
      ->setReadOnly(FALSE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the summary entity.'))
      ->setReadOnly(TRUE);

    $fields['last_modified'] = BaseFieldDefinition::create('timestamp')
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

    $fields['date_issued'] = BaseFieldDefinition::create('timestamp')
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
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
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
        'weight' => 35,
      ])
      ->setDisplayOptions('form', [
        'weight' => 35,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['granules_link'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Granules Link URL'))
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

    $fields['package_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Package ID'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 45,
      ])
      ->setDisplayOptions('form', [
        'weight' => 45,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['package_link'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Package Link URL'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 50,
      ])
      ->setDisplayOptions('form', [
        'weight' => 50,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['granule_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Granule ID'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 55,
      ])
      ->setDisplayOptions('form', [
        'weight' => 55,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['downloads'] = BaseFieldDefinition::create('downloads')
      ->setLabel(t('Downloads'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 60,
      ])
      ->setDisplayOptions('form', [
        'type' => 'downloads_field_type',
        'weight' => 60,
      ])
      ->setCardinality(1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['granule_class'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Granule Class'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 65,
      ])
      ->setDisplayOptions('form', [
        'weight' => 65,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sub_granule_class'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Sub Granule Class'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 70,
      ])
      ->setDisplayOptions('form', [
        'weight' => 70,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['heading'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Heading'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 75,
      ])
      ->setDisplayOptions('form', [
        'weight' => 75,
      ])
      ->setCardinality(1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['granule_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Granule Number'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 80,
      ])
      ->setDisplayOptions('form', [
        'weight' => 80,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
