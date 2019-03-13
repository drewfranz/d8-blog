<?php

namespace Drupal\Tests\path\Functional;

use Drupal\Core\Language\LanguageInterface;

/**
 * Tests alias langcode for multilingual nodes.
 *
 * @group path
 */
class PathTranslationTest extends PathTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'path',
    'locale',
    'content_translation',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create and log in user.
    $permissions = [
      'access administration pages',
      'administer content translation',
      'administer content types',
      'administer languages',
      'administer url aliases',
      'create content translations',
      'create page content',
      'create url aliases',
      'edit any page content',
      'translate any entity',
    ];
    $user = $this->drupalCreateUser($permissions);
    $this->drupalLogin($user);

    // Enable French language.
    $edit = [
      'predefined_langcode' => 'fr',
    ];
    $this->drupalPostForm('admin/config/regional/language/add', $edit,
      t('Add language'));
  }

  /**
   * Tests the alias langcode for untranslatable node.
   */
  public function testAliasUntranslatableNode() {
    // Create a node.
    $node_storage = $this->container->get('entity.manager')->getStorage('node');
    $node         = $this->drupalCreateNode([
      'type' => 'page',
      'langcode' => 'en',
    ]);
    $alias = $this->randomMachineName();

    // Edit the node to set the alias.
    $edit = [
      'path[0][alias]' => '/' . $alias,
    ];
    $this->drupalPostForm('node/' . $node->id() . '/edit', $edit, t('Save'));

    // Tests that the alias works.
    $this->drupalGet($alias);
    $this->assertText($node->body->value);

    // Tests that the alias was saved with 'und' langcode.
    $conditions = [
      'source' => '/node/' . $node->id(),
      'alias' => '/' . $alias,
    ];
    $path = $this->container->get('path.alias_storage')
      ->load($conditions);
    $this->assertEqual($path['langcode'],
      LanguageInterface::LANGCODE_NOT_SPECIFIED);
  }

  /**
   * Tests the alias langcode for translatable node.
   */
  public function testAliasLangcode() {
    // Tests the langcode for translatable path.
    // It should be set to the node's langcode.
    $english_alias = $this->randomMachineName();
    $french_alias  = $this->randomMachineName();
    $this->doTestAliasLangcode(TRUE, $english_alias, $french_alias, 'en', 'fr');

    // Tests the langcode for untranslatable path.
    // It should be set to not specified.
    $english_alias = $this->randomMachineName();
    $not_specified = LanguageInterface::LANGCODE_NOT_SPECIFIED;
    $this->doTestAliasLangcode(FALSE, $english_alias, $english_alias,
      $not_specified, $not_specified);
  }

  /**
   * Helper method to test aliases' langcode.
   */
  protected function doTestAliasLangcode(
    $translate_path,
    $english_alias,
    $french_alias,
    $expected_en,
    $expected_fr
  ) {
    // Enable translation for page nodes.
    $edit = [
      'entity_types[node]' => 1,
      'settings[node][page][translatable]' => 1,
      'settings[node][page][fields][path]' => $translate_path,
      'settings[node][page][fields][body]' => 1,
      'settings[node][page][settings][language][language_alterable]' => 1,
    ];
    $this->drupalPostForm('admin/config/regional/content-language', $edit,
      t('Save configuration'));

    // Clear caches.
    \Drupal::entityManager()->clearCachedDefinitions();

    // Create a node.
    $node_storage = $this->container->get('entity.manager')->getStorage('node');
    $english_node = $this->drupalCreateNode([
      'type' => 'page',
      'langcode' => 'en',
    ]);

    // Edit the node to set the alias.
    $edit_en = [
      'path[0][alias]' => '/' . $english_alias,
    ];
    $this->drupalPostForm('node/' . $english_node->id() . '/edit', $edit_en,
      t('Save'));

    // Translate the node into French.
    $edit_fr = [
      'title[0][value]' => $this->randomMachineName(),
      'body[0][value]' => $this->randomMachineName(),
      'path[0][alias]' => '/' . $french_alias,
    ];
    $this->drupalPostForm('node/' . $english_node->id() . '/translations/add/en/fr',
      $edit_fr, t('Save (this translation)'));

    // Clear the path lookup cache.
    $this->container->get('path.alias_manager')->cacheClear();

    // Languages are cached on many levels, and we need to clear those caches.
    $this->container->get('language_manager')->reset();
    $this->rebuildContainer();

    // Ensure the node was created.
    $node_storage->resetCache([$english_node->id()]);
    $english_node = $node_storage->load($english_node->id());
    $this->assertTrue($english_node->hasTranslation('fr'));
    $french_translation = $english_node->getTranslation('fr');

    // Tests that both aliases work.
    $this->drupalGet($edit_en['path[0][alias]']);
    $this->assertText($english_node->body->value);
    $this->drupalGet('fr' . $edit_fr['path[0][alias]']);
    $this->assertText($french_translation->body->value);

    // Tests that the English alias was saved with the expected langcode.
    $conditions = [
      'source' => '/node/' . $english_node->id(),
      'alias' => $edit_en['path[0][alias]'],
    ];
    $path_en = $this->container->get('path.alias_storage')
      ->load($conditions);
    $this->assertEqual($path_en['langcode'], $expected_en);

    // Tests that the French alias was saved with the expected langcode.
    $conditions = [
      'source' => '/node/' . $french_translation->id(),
      'alias' => $edit_fr['path[0][alias]'],
    ];
    $path_fr = $this->container->get('path.alias_storage')
      ->load($conditions);
    $this->assertEqual($path_fr['langcode'], $expected_fr);
  }

}
