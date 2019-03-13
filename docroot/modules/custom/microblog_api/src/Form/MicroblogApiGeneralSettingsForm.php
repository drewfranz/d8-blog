<?php

namespace Drupal\microblog_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
//use Drupal\easy_breadcrumb\EasyBreadcrumbConstants;

/**
 * Build Micro.blog API settings form.
 */
class MicroblogApiGeneralSettingsForm extends ConfigFormBase {

 /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'microblog_api_general_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['microblog_api.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('microblog_api.settings');

    // Fieldset for grouping general settings fields.
    $fieldset_general = [
      '#type' => 'fieldset',
      '#title' => $this->t('General settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    
    $fieldset_general['access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Micro.blog Access Token'),
      '#description' => $this->t('Micro.blog doesn’t have normal account passwords, but it does have app tokens, which are like app-specific passwords. You can generate a new token under Account → Edit Apps to use in an app, and that app will have full access to your Micro.blog account.'),
      '#default_value' => $config->get('access_token')),
    ];
    
    $form = [];

    // Inserts the fieldset for grouping general settings fields.
    $form['microblog_api'] = $fieldset_general;

    return parent::buildForm($form, $form_state);
  }
  
   /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('microblog_api.settings');

    $config
      ->set('access_token', $form_state->getValue('access_token'))
      ->save();

    parent::submitForm($form, $form_state);
    }
}