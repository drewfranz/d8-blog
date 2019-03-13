<?php

/**
 * Post update functions for path.
 */

use Drupal\Core\Language\LanguageInterface;

/**
 * Update langcodes of untranslatable path alias fields.
 */
function path_post_update_fix_path_alias_langcodes() {
  $entity_manager = \Drupal::service('entity_type.bundle.info');
  $alias_storage = \Drupal::service('path.alias_storage');
  $connection = \Drupal::service('database');

  $entity_types = [
    'node' => [
      'bundle_column' => 'type',
      'source_path' => '/node/',
    ],
    'taxonomy_term' => [
      'bundle_column' => 'vid',
      'source_path' => '/taxonomy/term/',
    ],
  ];

  foreach ($entity_types as $entity_type_id => $info) {
    foreach ($entity_manager->getBundleInfo($entity_type_id) as $bundle_id => $bundle) {
      // Update path alias langcodes only if the bundle or the path field are not translatable.
      if (isset($bundle['translatable']) && $bundle['translatable'] === FALSE || \Drupal::config("core.base_field_override.$entity_type_id.$bundle_id.path")
        ->get('translatable') === FALSE) {
        $ids = \Drupal::entityQuery($entity_type_id)
          ->condition($info['bundle_column'], $bundle_id)
          ->execute();
        foreach ($ids as $id) {
          // Get all path aliases associated with this ID.
          $aliases = $connection->select('url_alias', 'a')
            ->fields('a', ['source', 'alias', 'langcode', 'pid'])
            ->condition('source', $info['source_path'] . $id)
            ->condition('langcode', LanguageInterface::LANGCODE_NOT_SPECIFIED,
              '!=')
            ->execute()
            ->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($aliases as $alias) {
            // Make sure we don't create duplicated path aliases.
            if (!$alias_storage->load([
              'alias' => $alias['alias'],
              'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
            ])) {
              $alias_storage->save($alias['source'], $alias['alias'],
                LanguageInterface::LANGCODE_NOT_SPECIFIED, $alias['pid']);
            }
          }
        }
      }
    }
  }
}
