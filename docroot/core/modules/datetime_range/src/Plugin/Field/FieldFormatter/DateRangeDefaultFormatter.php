<?php

namespace Drupal\datetime_range\Plugin\Field\FieldFormatter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldFormatter\DateTimeDefaultFormatter;
use Drupal\datetime_range\DateTimeRangeTrait;

/**
 * Plugin implementation of the 'Default' formatter for 'daterange' fields.
 *
 * This formatter renders the data range using <time> elements, with
 * configurable date formats (from the list of configured formats) and a
 * separator.
 *
 * @FieldFormatter(
 *   id = "daterange_default",
 *   label = @Translation("Default"),
 *   field_types = {
 *     "daterange"
 *   }
 * )
 */
class DateRangeDefaultFormatter extends DateTimeDefaultFormatter {

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
