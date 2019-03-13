<?php

namespace Drupal\datetime_range;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides friendly methods for datetime range.
 */
trait DateTimeRangeTrait {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'fromto' => 'both',
      'separator' => '-',
    ];
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
            $elements[$delta]['start_date'] = $this->buildDateWithIsoAttribute($start_date);
          }
          if ($this->startDateIsDisplayed() && $this->endDateIsDisplayed()) {
            $elements[$delta]['separator'] = ['#plain_text' => ' ' . $separator . ' '];
          }
          if ($this->endDateIsDisplayed()) {
            $elements[$delta]['end_date'] = $this->buildDateWithIsoAttribute($end_date);
          }
        }
        else {
          $elements[$delta] = $this->buildDateWithIsoAttribute($start_date);
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
    $form['fromto'] = [
      '#type' => 'select',
      '#title' => $this->t('Display'),
      '#options' => $this->getFromToOptions(),
      '#default_value' => $this->getSetting('fromto'),
    ];

    $form['separator'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Date separator'),
      '#description' => $this->t('The string to separate the start and end dates'),
      '#default_value' => $this->getSetting('separator'),
      '#states' => [
        'visible' => [
          ':input[name="options[settings][fromto]"]' => ['value' => 'both'],
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    if ($fromto = $this->getSetting('fromto')) {
      $fromto_options = $this->getFromToOptions();
      if (isset($fromto_options[$fromto])) {
        $summary[] = $fromto_options[$fromto];
      }
    }

    if ($separator = $this->getSetting('separator') && $this->getSetting('fromto') == 'both') {
      $summary[] = $this->t('Separator: %separator', ['%separator' => $separator]);
    }

    return $summary;
  }

  /**
   * Returns a list of possible values for the 'fromto' setting.
   *
   * @return array
   *   A list of 'fromto' options.
   */
  public function getFromToOptions() {
    return [
      'both' => $this->t('Display both Start and End dates'),
      'start_date' => $this->t('Display Start date only'),
      'end_date' => $this->t('Display End date only'),
    ];
  }

  /**
   * Returns whether or not the start date should be displayed.
   *
   * @return bool
   *   True if the start date should be displayed. False otherwise.
   */
  public function startDateIsDisplayed() {
    switch ($this->getSetting('fromto')) {
      case 'both':
      case 'start_date':
        return TRUE;
    }

    return FALSE;
  }

  /**
   * Returns whether or not the end date should be displayed.
   *
   * @return bool
   *   True if the end date should be displayed. False otherwise.
   */
  public function endDateIsDisplayed() {
    switch ($this->getSetting('fromto')) {
      case 'both':
      case 'end_date':
        return TRUE;
    }

    return FALSE;
  }

}
