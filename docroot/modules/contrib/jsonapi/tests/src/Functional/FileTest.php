<?php

namespace Drupal\Tests\jsonapi\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Tests\jsonapi\Traits\CommonCollectionFilterAccessTestPatternsTrait;
use Drupal\Tests\rest\Functional\BcTimestampNormalizerUnixTestTrait;
use Drupal\user\Entity\User;
use GuzzleHttp\RequestOptions;

/**
 * JSON API integration test for the "File" content entity type.
 *
 * @group jsonapi
 */
class FileTest extends ResourceTestBase {

  use BcTimestampNormalizerUnixTestTrait;
  use CommonCollectionFilterAccessTestPatternsTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['file', 'user'];

  /**
   * {@inheritdoc}
   */
  protected static $entityTypeId = 'file';

  /**
   * {@inheritdoc}
   */
  protected static $resourceTypeName = 'file--file';

  /**
   * {@inheritdoc}
   *
   * @var \Drupal\file\FileInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  protected static $patchProtectedFieldNames = [
    'uri' => NULL,
    'filemime' => NULL,
    'filesize' => NULL,
    'status' => NULL,
    'changed' => NULL,
  ];

  /**
   * The file author.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $author;

  /**
   * {@inheritdoc}
   */
  protected function setUpAuthorization($method) {
    switch ($method) {
      case 'GET':
        $this->grantPermissionsToTestedRole(['access content']);
        break;

      case 'PATCH':
      case 'DELETE':
        // \Drupal\file\FileAccessControlHandler::checkAccess() grants 'update'
        // and 'delete' access only to the user that owns the file. So there is
        // no permission to grant: instead, the file owner must be changed from
        // its default (user 1) to the current user.
        $this->makeCurrentUserFileOwner();
        break;
    }
  }

  /**
   * Makes the current user the file owner.
   */
  protected function makeCurrentUserFileOwner() {
    $account = User::load(2);
    $this->entity->setOwnerId($account->id());
    $this->entity->setOwner($account);
    $this->entity->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function createEntity() {
    $this->author = User::load(1);

    $file = File::create();
    $file->setOwnerId($this->author->id());
    $file->setFilename('drupal.txt');
    $file->setMimeType('text/plain');
    $file->setFileUri('public://drupal.txt');
    $file->set('status', FILE_STATUS_PERMANENT);
    $file->save();

    file_put_contents($file->getFileUri(), 'Drupal');

    return $file;
  }

  /**
   * {@inheritdoc}
   */
  protected function createAnotherEntity($key) {
    /* @var \Drupal\file\FileInterface $duplicate */
    $duplicate = parent::createAnotherEntity($key);
    $duplicate->setFileUri("public://$key.txt");
    $duplicate->save();
    return $duplicate;
  }

  /**
   * {@inheritdoc}
   */
  protected function getExpectedDocument() {
    $self_url = Url::fromUri('base:/jsonapi/file/file/' . $this->entity->uuid())->setAbsolute()->toString(TRUE)->getGeneratedUrl();
    $normalization = [
      'jsonapi' => [
        'meta' => [
          'links' => [
            'self' => 'http://jsonapi.org/format/1.0/',
          ],
        ],
        'version' => '1.0',
      ],
      'links' => [
        'self' => $self_url,
      ],
      'data' => [
        'id' => $this->entity->uuid(),
        'type' => 'file--file',
        'links' => [
          'self' => $self_url,
        ],
        'attributes' => [
          'changed' => $this->entity->getChangedTime(),
          // @todo uncomment this in https://www.drupal.org/project/jsonapi/issues/2929932
          /* 'changed' => $this->formatExpectedTimestampItemValues($this->entity->getChangedTime()), */
          'created' => (int) $this->entity->getCreatedTime(),
          // @todo uncomment this in https://www.drupal.org/project/jsonapi/issues/2929932
          /* 'created' => $this->formatExpectedTimestampItemValues((int) $this->entity->getCreatedTime()), */
          'fid' => 1,
          'filemime' => 'text/plain',
          'filename' => 'drupal.txt',
          'filesize' => (int) $this->entity->getSize(),
          'langcode' => 'en',
          'status' => TRUE,
          // @todo Decide what to do with this in https://www.drupal.org/project/jsonapi/issues/2926463
          'url' => base_path() . $this->siteDirectory . '/files/drupal.txt',
          'uri' => [
            'url' => base_path() . $this->siteDirectory . '/files/drupal.txt',
            'value' => 'public://drupal.txt',
          ],
          'uuid' => $this->entity->uuid(),
        ],
        'relationships' => [
          'uid' => [
            'data' => [
              'id' => $this->author->uuid(),
              'type' => 'user--user',
            ],
            'links' => [
              'related' => $self_url . '/uid',
              'self' => $self_url . '/relationships/uid',
            ],
          ],
        ],
      ],
    ];
    // @todo Remove this modification when JSON API requires Drupal 8.5 or newer, and do an early return above instead.
    if (floatval(\Drupal::VERSION) < 8.5) {
      $normalization['data']['attributes']['uri'] = $normalization['data']['attributes']['uri']['url'];
    }
    return $normalization;

  }

  /**
   * {@inheritdoc}
   */
  protected function getPostDocument() {
    return [
      'data' => [
        'type' => 'file--file',
        'attributes' => [
          'filename' => 'drupal.txt',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function testPostIndividual() {
    // @todo https://www.drupal.org/node/1927648
    $this->markTestSkipped();
  }

  /**
   * {@inheritdoc}
   */
  protected function getExpectedUnauthorizedAccessMessage($method) {
    if ($method === 'GET') {
      return "The 'access content' permission is required.";
    }
    // @todo Make this unconditional when JSON API requires Drupal 8.6 or newer.
    if (floatval(\Drupal::VERSION) >= 8.6 && ($method === 'PATCH' || $method === 'DELETE')) {
      return "Only the file owner can update or delete the file entity.";
    }
    return parent::getExpectedUnauthorizedAccessMessage($method);
  }

  /**
   * {@inheritdoc}
   */
  public function testGetIndividual() {
    // @todo Remove when JSON API requires Drupal 8.5 or newer.
    // @see https://www.drupal.org/project/drupal/issues/2866666
    if (floatval(\Drupal::VERSION) < 8.5) {
      $this->markTestSkipped('File entities had a dysfunctional access control handler until 8.5, this is necessary for this test coverage to work.');
    }
    return parent::testGetIndividual();
  }

  /**
   * {@inheritdoc}
   */
  public function testCollectionFilterAccess() {
    $label_field_name = 'filename';
    // Verify the expected behavior in the common case: when the file is public.
    $this->doTestCollectionFilterAccessBasedOnPermissions($label_field_name, 'access content');

    $collection_url = Url::fromRoute('jsonapi.entity_test--bar.collection');
    $collection_filter_url = $collection_url->setOption('query', ["filter[spotlight.$label_field_name]" => $this->entity->label()]);
    $request_options = [];
    $request_options[RequestOptions::HEADERS]['Accept'] = 'application/vnd.api+json';
    $request_options = NestedArray::mergeDeep($request_options, $this->getAuthenticationRequestOptions());

    // 1 result because the current user is the file owner, even though the file
    // is private.
    $this->entity->setFileUri('private://drupal.txt');
    $this->entity->setOwner($this->account);
    $this->entity->save();
    $response = $this->request('GET', $collection_filter_url, $request_options);
    $doc = Json::decode((string) $response->getBody());
    $this->assertCount(1, $doc['data']);

    // 0 results because the current user is no longer the file owner and the
    // file is private.
    $this->entity->setOwner(User::load(0));
    $this->entity->save();
    $response = $this->request('GET', $collection_filter_url, $request_options);
    $doc = Json::decode((string) $response->getBody());
    $this->assertCount(0, $doc['data']);
  }

}
