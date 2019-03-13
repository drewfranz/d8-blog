<?php

namespace Drupal\path\Plugin\Field\FieldType;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Language\Language;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Represents a configurable entity path field.
 */
class PathFieldItemList extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {
    // Default the langcode to the current language if this is a new entity or
    // there is no alias for an existent entity.
    $value = ['langcode' => !$this->getFieldDefinition()->isTranslatable() ? Language::LANGCODE_NOT_SPECIFIED : $this->getLangcode()];

    $entity = $this->getEntity();
    if (!$entity->isNew()) {
      $conditions = [
        'source' => '/' . $entity->toUrl()->getInternalPath(),
        'langcode' => $this->getLangcode(),
      ];
      $alias = \Drupal::service('path.alias_storage')->load($conditions);
      if ($alias === FALSE) {
        // Fall back to non-specific language.
        if ($this->getLangcode() !== LanguageInterface::LANGCODE_NOT_SPECIFIED) {
          $conditions['langcode'] = LanguageInterface::LANGCODE_NOT_SPECIFIED;
          $alias = \Drupal::service('path.alias_storage')->load($conditions);
        }
      }

      if ($alias) {
        $value = $alias;
      }
    }

    $this->list[0] = $this->createItem(0, $value);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultAccess($operation = 'view', AccountInterface $account = NULL) {
    if ($operation == 'view') {
      return AccessResult::allowed();
    }
    return AccessResult::allowedIfHasPermissions($account, ['create url aliases', 'administer url aliases'], 'OR')->cachePerPermissions();
  }

  /**
   * {@inheritdoc}
   */
  public function delete() {
    // Delete all aliases associated with this entity in the current language.
    $entity = $this->getEntity();
    $entityTypeId = $entity->getEntityTypeId();
    $bundleId = $entity->bundle();
    $conditions = [
      'source' => '/' . $entity->toUrl()->getInternalPath(),
      'langcode' => $entity->language()->getId(),
    ];
    $entity_langcode = $entity->language()->getId();
    $original_entity_langcode = $entity->getUntranslated()->language()->getId();
    // If the path field is not translatable  and compare langauge code to check
    // entity being deleted is a source entity not translated entity then
    // delete the path alias with the langcode 'und'.
    if ($entity_langcode == $original_entity_langcode &&
        \Drupal::config("core.base_field_override" . $entityTypeId . $bundleId . "path")
          ->get('translatable') === FALSE) {
      $conditions['langcode'] = LanguageInterface::LANGCODE_NOT_SPECIFIED;
    }
    \Drupal::service('path.alias_storage')->delete($conditions);
  }

}
