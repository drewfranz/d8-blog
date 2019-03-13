<?php

namespace Drupal\datetime_range\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldFormatter\DateTimePlainFormatter;
use Drupal\datetime_range\DateTimeRangeTrait;

/**
 * Plugin implementation of the 'Plain' formatter for 'daterange' fields.
 *
 * This formatter renders the data range as a plain text string, with a
 * configurable separator using an ISO-like date format string.
 *
 * @FieldFormatter(
 *   id = "daterange_plain",
 *   label = @Translation("Plain"),
 *   field_types = {
 *     "daterange"
 *   }
 * )
 */
class DateRangePlainFormatter extends DateTimePlainFormatter {

  use DateTimeRangeTrait {
    DateTimeRangeTrait::defaultSettings as traitDefaultSettings;
    DateTimeRangeTrait::settingsForm as traitSettingsForm;
    DateTimeRangeTrait::settingsSummary as traitSettingsSummary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return static::traitDefaultSettings() + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $separator = $this->getSetting('separator');

    foreach ($items as $delta => $item) {
      if (!empty($item->start_date) && !empty($item->end_date)) {
        /** @var \Drupal\Core\Datetime\DrupalDateTime $start_date */
        $start_date = $item->start_date;
        /** @var \Drupal\Core\Datetime\DrupalDateTime $end_date */
        $end_date = $item->end_date;

        if ($start_date->getTimestamp() !== $end_date->getTimestamp()) {
          $elements[$delta] = [];
          if ($this->startDateIsDisplayed()) {
            $elements[$delta]['start_date'] = $this->buildDate($start_date);
          }
          if ($this->startDateIsDisplayed() && $this->endDateIsDisplayed()) {
            $elements[$delta]['separator'] = ['#plain_text' => ' ' . $separator . ' '];
          }
          if ($this->endDateIsDisplayed()) {
            $elements[$delta]['end_date'] = $this->buildDate($end_date);
          }
        }
        else {
          $elements[$delta] = $this->buildDate($start_date);

          if (!empty($item->_attributes)) {
            $elements[$delta]['#attributes'] += $item->_attributes;
            // Unset field item attributes since they have been included in the
            // formatter output and should not be rendered in the field template.
            unset($item->_attributes);
          }
        }
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    $form = $this->traitSettingsForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return array_merge(parent::settingsSummary(), $this->traitSettingsSummary());
  }

}
