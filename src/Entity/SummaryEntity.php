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
 *       "default" = "Drupal\govinfo\Form\TweetEntityForm",
 *       "add" = "Drupal\govinfo\Form\TweetEntityForm",
 *       "edit" = "Drupal\govinfo\Form\TweetEntityForm",
 *       "delete" = "Drupal\govinfo\Form\TweetEntityDeleteForm",
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
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
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
  public function getFeedMachineName() {
    return $this->get('feed_machine_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFeedMachineName($feed_machine_name) {
    $this->set('feed_machine_name', $feed_machine_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPackageId() {
    return $this->get('packageId')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTweetId($packageId) {
    $this->set('packageId', $packageId);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTweetTitle() {
    return $this->get('tweet_title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTweetTite($tweet_title) {
    $this->set('tweet_title', $tweet_title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTweetFullText() {
    return $this->get('tweet_full_text')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTweetFullText($tweet_full_text) {
    $this->set('tweet_full_text', $tweet_full_text);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTweetUserProfileId() {
    return $this->get('tweet_user_profile_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTweetUserProfileId($tweet_user_profile_id) {
    $this->set('tweet_user_profile_id', $tweet_user_profile_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTweetUserProfile() {
    $id = $this->get('tweet_user_profile_id');
    return \Drupal::entityTypeManager()->getStorage('user')->load($id);
  }

  /**
   * {@inheritdoc}
   */
  public function getIsVerifiedUser() {
    return ($this->get('is_verified_user') != 'Off') ? TRUE : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function setIsVerifiedUser($is_verified_user) {
    $this->set('is_verified_user', $is_verified_user);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLinkedImages() {
    $files = $this->get('linked_images')->getValue();
    $images = [];
    foreach ($files as $file) {
      $fo = File::load($file['target_id']);
      $images[] = $fo;
    }
    return $images;
  }

  /**
   * {@inheritdoc}
   */
  public function setLinkedImages($images) {


  }

  /**
   * {@inheritdoc}
   */
  public function getLinkedImageUrls() {
    $files = $this->getLinkedImages();
    $urls = [];
    foreach ($files as $file) {
      $file_uri = $file->getFileUri();
      // I can't believe this will survive Drupal 9 but there is no deprecation notice on it yet.
      $urls[] = file_create_url($file_uri);
    }
    return $urls;
  }

  /**
   * {@inheritdoc}
   */
  private function getTags($tags) {
    $hashtags = $this->get($tags)->getValue();
    $tags = [];
    if (!empty($hashtags)) {
      foreach($hashtags as $key => $term) {
        $tag = $this->entityTypeManager()->getStorage('taxonomy_term')->load($term['target_id'])->values;
        $tags[]['name'] = $tag['name']['x-default'];
        $tags[]['tid'] = $tag['tid']['x-default'];
      }
    }
    return $tags;
  }

  /**
   * {@inheritdoc}
   */
  private function setTags($entities, $taxonomy) {
    $tids = [];
    foreach($entities as $entity) {
      switch($taxonomy) {
        case 'hashtag_terms':
          $taxonomy_name = $entity->text;
          break;
        case 'user_mention_terms':
          $taxonomy_name = $entity->screen_name;
          break;
        default:
          break;
      }

      $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($taxonomy);
      if (!empty($terms)) {
        foreach ($terms as $term) {
          if ($term->term_name == $taxonomy_name) {
            $tid = $term->id;
          }
        }
        if (!empty($tid)) {
          $new_term = \Drupal\taxonomy\Entity\Term::create([
            'vid' => $taxonomy,
            'name' => $taxonomy_name,
          ]);
          $tid = $new_term->tid;
          $new_term->save();
        }
        $tids[] = $tid;
      }
    }
    return $tids;
  }

  /**
   * {@inheritdoc}
   */
  public function getHashtags() {
    return $this->getTags('hashtags');
  }

   /**
   * {@inheritdoc}
   */
  public function setHashtags($hashtags) {
    return $this->get('hashtags');
  }

  /**
   * {@inheritdoc}
   */
  public function getUserMentionsTags() {
    return $this->getTags('user_mentions_tags');
  }

  /**
   * {@inheritdoc}
   */
  public function getGeographicCoordinatres() {
    return $this->get('geographic_coordinates')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setGeographicCoordinates($geographic_coordinates) {
    $this->set('geographic_coordinates', $geographic_coordinates);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGeographicPlace() {
    return $this->get('geographic_place')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setGeographicPlace($geographic_place) {
    $this->set('geographic_place', $geographic_place);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSource() {
    return $this->get('source')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSource($source) {
    $this->set('source', $source);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserMentions() {
    $mentions = $this->get('user_mentions');
    foreach ($mentions as $mentions) {

    }
  }

  /**
   * {@inheritdoc}
   */
  public function isQuotedOrRepliedTweet() {
    return ($this->get('quoted_or_replied_tweet') != 'Off') ? TRUE : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function setQuotedOrRepliedTweet($quoted_replied) {
    $this->set('quoted_or_replied_tweet', $quoted_replied);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuotedStatusId() {
    return $this->get('geographic_place')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setQuotedStatusID($geographic_location) {
    $this->set('geographic_place', $geographic_place);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getInReplyToStatusID() {
    return $this->get('in_reply_to_status_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setInReplyToStatusID($in_reply_to_status_id) {
    $this->set('in_reply_to_status_id', $in_reply_to_status_id);
    return $this;
  }

  public function getLinkToTweet() {
    return $this->get('link_to_tweet')->value;
  }

  public function setLinkToTweet($link_to_tweet) {
    $this->set('link_to_tweet', $link_to_tweet);
    return $this;
  }

  public function getOwnerProfileId() {
    return $this->get('owner_profile_id')->value;
  }

  public function setOwnerProfileId($owner_profile_id) {
    $this->set('owner_profile_id', $owner_profile_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setProfileImage($image) {


  }

  /**
   * {@inheritdoc}
   */
  public function getProfileImage() {
    $file = $this->getProfileImage();

    $file_uri = $file->getFileUri();
    // I can't believe this will survive Drupal 9 but there is no deprecation notice on it yet.
    $urls = file_create_url($file_uri);
    return $url;
  }


  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the tweet entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the tweet entity.'))
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['feed_machine_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Feed machine name'))
      ->setDescription(t('The machine name of the feed that owns this tweet.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['tweet_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tweet ID'))
      ->setDescription(t('The Twitter ID for this tweet.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['tweet_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tweet Title'))
      ->setDescription(t('The cleansed title for this tweet. For administrative use only.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['tweet_full_text'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Tweet Full Text'))
      ->setDescription(t('The contents of the tweet. Untruncated.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 7,
      ])
      ->setDisplayOptions('form', [
        'weight' => 7,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['tweet_user_profile_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tweet Author ID'))
      ->setDescription(t('The Twitter ID of the author of this tweet.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 3,
      ])
      ->setDisplayOptions('form', [
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['is_verified_user'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Is this a verified user?'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['linked_images'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Linked Images'))
      ->setDescription(t('Images linked in tweets.'))
      ->setSettings([
        'uri_scheme' => 'public',
        'file_directory' => 'tweet-feed-tweet-images/[date:custom:Y]-[date:custom:m]',
        'alt_field_required' => FALSE,
        'file_extensions' => 'png jpg jpeg gif',
      ])
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 8,
      ))
      ->setDisplayOptions('form', array(
        'weight' => 8,
      ))
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hashtags'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Hashtags Used'))
      ->setDescription(t('Any hashtags that are contained in a tweet.'))
      ->setRevisionable(FALSE)
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'twitter_hashtag_terms' => 'twitter_hashtag_terms',
        ],
      ])
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'weight' => 9,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 9,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_mentions'] = BaseFieldDefinition::create('user_mentions_field_type')
      ->setLabel(t('User Mentions'))
      ->setDescription(t('Users mentioned in the tweet'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 11,
      ])
      ->setDisplayOptions('form', [
        'type' => 'user_mentions_field_type',
        'weight' => 11,
      ])
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['geographic_coordinates'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Geographic Coordinates'))
      ->setDescription(t('The geographic coordinates of a tweet if provided.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 13,
      ])
      ->setDisplayOptions('form', [
        'weight' => 13,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['geographic_place'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Geographic Place'))
      ->setDescription(t('The geographic location of a tweet if provided.'))
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

    $fields['source'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tweet Source'))
      ->setDescription(t('The name of the application used to generate a tweet.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 16,
      ])
      ->setDisplayOptions('form', [
        'weight' => 16,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_mentions_tags'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Mentions'))
      ->setDescription(t('The users mentioned in a tweet in the form of tags.'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'twitter_user_mention_terms' => 'twitter_user_mention_terms',
        ],
      ])
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'weight' => 17,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 17,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['quoted_or_replied_tweet'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Quoted or Replied Tweet?'))
      ->setDescription(t('Is this tweet a re-tweet with a comment or a tweet that was replied to? These are not displayed outside the context of the re-tweet.'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['quoted_status_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tweet which was re-tweeted for comments'))
      ->setDescription(t('This is the ID of the tweet which was re-tweeted with comments.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 18,
      ])
      ->setDisplayOptions('form', [
        'weight' => 18,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['in_reply_to_status_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Reply to status id.'))
      ->setDescription(t('This is the ID of the tweet which was being replied to.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 19,
      ])
      ->setDisplayOptions('form', [
        'weight' => 19,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['link_to_tweet'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tweet Link'))
      ->setDescription(t('The URL that will take the user to the tweet on Twitter.'))
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

    $fields['owner_profile_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tweet Owner Profile ID'))
      ->setDescription(t('The Twitter ID of the owner of this tweet.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 21,
      ])
      ->setDisplayOptions('form', [
        'weight' => 21,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['profile_image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Profile Image'))
      ->setDescription(t('The Profile Image of the Tweeter.'))
      ->setSettings([
        'uri_scheme' => 'public',
        'file_directory' => 'tweet-feed-tweet-profile-images/[date:custom:Y]-[date:custom:m]',
        'alt_field_required' => FALSE,
        'file_extensions' => 'png jpg jpeg gif',
      ])
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 22,
      ))
      ->setDisplayOptions('form', array(
        'weight' => 22,
      ))
      ->setCardinality(1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}