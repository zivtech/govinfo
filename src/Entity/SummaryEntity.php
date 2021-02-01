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
use Drupal\user\EntityOwnerTrait;
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
 *   label = @Translation("govinfo Summary"),
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
 *     "id" = "sid",
 *     "owner" = "uid",
 *     "uid" = "uid",
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

  use EntityOwnerTrait;

  public function __construct() {
    parent::__construct([], 'govinfo_summary');
  }

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values): void {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }


  public function setOwner($owner) {}
  public function getOwner() {}
  public function setOwnerId($ownerId) {}
  public function getOwnerId() {}

  public function setLastModified($timestamp): self {
    $this->set('last_modified', $timestamp);
    return $this;
  }

  public function getLastModified(): int {
    return $this->get('last_modified')->value;
  }

  public function setDateIssued($timestamp): self {
    $this->set('date_issued', $timestamp);
    return $this;
  }

  public function getDateIssued(): int {
    return $this->get('date_issued')->value;
  }

  public function setTitle($title): self {
    $this->set('title', $title);
    return $this;
  }

  public function getTitle() {
    print_r($this->toArray());
    exit();

    return $this->get('title')->value;
  }

  public function setCollectionCode($collection_code): self {
    $this->set('collection_code', $collection_code);
    return $this;
  }

  public function getCollectionCode(): string {
    return $this->get('collectionCode')->value;
  }

  public function setCollectionName($collection_name): self {
    $this->set('collection_name', $collection_name);
    return $this;
  }

  public function getCollectionName(): string {
    return $this->get('collection_name')->value;
  }

  public function setCategory($category): self {
    $this->set('category', $category);
    return $this;
  }

  public function getCategory(): string {
    return $this->get('category')->value;
  }

  public function setDetailsLink($url): self {
    $value = [
      'uri' => $url,
      'title' => t('Details'),
    ];
    $this->set('details_link', $value);
    return $this;
  }

  public function getDetailsLink() {
    return $this->get('details_link');
  }

  public function setGranulesLink($url): self {
    $value = [
      'uri' => $url,
      'title' => t('Granules'),
    ];
    $this->set('granules_link', $value);
    return $this;
  }

  public function getGranulesLink() {
    return $this->get('granules_link');
  }

  public function setPackageId($package_id): self {
    $this->set('package_id', $package_id);
    return $this;
  }

  public function getPackageId(): string {
    return $this->get('package_id')->value;
  }

  public function setDownloads($download) {
    if (!empty($download)) {
      $downloads = [];
      $downloads[] = $download;
      $this->set('downloads', $downloads);
    }
    return $this;
  }

  public function getDownloads() {
    return $this->get('downloads')->value;
  }

  public function setBranch($branch) {
    $this->set('branch', $branch);
    return $this;
  }

  public function getBranch() {
    return $this->get('branch')->value;
  }

  public function setPages($pages) {
    $this->set('pages', $pages);
    return $this;
  }

  public function getPages() {
    return $this->get('pages')->value;
  }

  public function setGovernmentAuthor($government_author1, $government_author2) {
    $government_author = [];
    $government_author[]['value'] = $government_author1;
    $government_author[]['value'] = $government_author2;
    $this->set('government_author', $government_author);
    return $this;
  }

  public function getGovernmentAuthor() {
    return $this->get('government_author')->value;
  }

  public function setSuDocClassNumber($sudoc_class_number) {
    $this->set('su_doc_class_number', $sudoc_class_number);
    return $this;
  }

  public function getSuDocClassNumber() {
    return $this->get('su_doc_class_number')->value;
  }

  public function setDocumentType($document_type) {
    $this->set('document_type', $document_type);
    return $this;
  }

  public function getDocumenType() {
    return $this->get('document_type')->value;
  }

  public function setCongress($congress) {
    $this->set('congress', $congress);
    return $this;
  }

  public function getCongress() {
    return $this->get('congress')->value;
  }

  public function setSession($session) {
    $this->set('session', $session);
    return $this;
  }

  public function getSession() {
    return $this->get('session')->value;
  }

  public function setCommittees($committees): self {
    $this->set('committees', $committees);
    return $this;
  }

  public function getCommittees() {
    return $this->get('commitees')->value;
  }

  public function setTitleNumber($title_number) {
    $this->set('title_number', $title_number);
    return $this;
  }

  public function getTitleNumber() {
    return $this->get('title_number')->value;
  }

  public function setPartRange($from, $to) {
    $range = [
      'from' => $from,
      'to' => $to,
    ];
    $this->set('part_range', $range);
    return $this;
  }

  public function getPartRange() {
    return $this->get('part_range')->value;
  }

  public function setVolumeCount($volume_count) {
    $this->set('volume_count', $volume_count);
    return $this;
  }

  public function getVolumeCount() {
    return $this->get('volume_count')->value;
  }

  public function setVolume($volume) {
    $this->set('volume', $volume);
    return $this;
  }

  public function getVolume() {
    return $this->get('volume')->value;
  }

  public function setPublisher($publisher): self {
    $this->set('publisher', $publisher);
    return $this;
  }

  public function getPublisher(): string {
    return $this->get('publisher')->value;
  }

  public function setOtherIdentifiers(array $other_identifiers): self {
    $oi = [];
    foreach ($other_identifiers as $identifier) {
      $oi[] = $identifier;
    }
    $this->set('other_identifiers', $oi);
    return $this;
  }

  public function getOtherIdentifiers() {
    return $this->get('other_identifiers');
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields += static::ownerBaseFieldDefinitions($entity_type);

    $fields['uid']
      ->setLabel(t('Authored by'))
      ->setDescription(t('The username of the content author.'))
      ->setRevisionable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE);


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
        'weight' => 45,
      ])
      ->setDisplayOptions('form', [
        'type' => 'downloads_field_type',
        'weight' => 45,
      ])
      ->setCardinality(1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['branch'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Branch'))
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

    $fields['pages'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Pages'))
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

    $fields['government_author'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Government Author'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 60,
      ])
      ->setDisplayOptions('form', [
        'weight' => 60,
      ])
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['su_doc_class_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Su Doc Class Number'))
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

    $fields['document_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Document Type'))
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

    $fields['congress'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Congress'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 72,
      ])
      ->setDisplayOptions('form', [
        'weight' => 72,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['session'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 74,
      ])
      ->setDisplayOptions('form', [
        'weight' => 74,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title_number'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Title Number'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 75,
      ])
      ->setDisplayOptions('form', [
        'weight' => 75,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['part_range'] = BaseFieldDefinition::create('part_range')
      ->setLabel(t('Part Range'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 80,
      ])
      ->setDisplayOptions('form', [
        'type' => 'part_range',
        'weight' => 80,
      ])
      ->setCardinality(1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['volume_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Volume Count'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 85,
      ])
      ->setDisplayOptions('form', [
        'weight' => 85,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['volume'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Volume'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 87,
      ])
      ->setDisplayOptions('form', [
        'weight' => 87,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['publisher'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Publisher'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 90,
      ])
      ->setDisplayOptions('form', [
        'weight' => 90,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['committees'] = BaseFieldDefinition::create('committees')
      ->setLabel(t('Committees'))
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
        'weight' => 93,
      ])
      ->setDisplayOptions('form', [
        'type' => 'downloads_field_type',
        'weight' => 93,
      ])
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['other_identifiers'] = BaseFieldDefinition::create('other_identifier')
      ->setLabel(t('Other Identifiers'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 95,
      ])
      ->setDisplayOptions('form', [
        'type' => 'other_identifiers',
        'weight' => 95,
      ])
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
