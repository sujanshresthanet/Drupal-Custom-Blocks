<?php

namespace Drupal\custom_taxonomy_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Custom Taxonomy Block' block.
 *
 * @Block(
 *   id = "custom_taxonomy_block",
 *   admin_label = @Translation("Custom Taxonomy Block"),
 *   category = @Translation("Custom Blocks")
 * )
 */
class CustomTaxonomyBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Retrieve block configuration.
    $config = $this->getConfiguration();
    // Retrieve vocabulary id from block configuration, default to empty string if not set.
    $vid = isset($config['vid']) ? $config['vid'] : '';

    $terms = [];
    // If vocabulary id is not empty, load the terms associated with it.
    if (!empty($vid)) {
      $terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadTree($vid);
    }

    // Return render array defining the block content.
    return [
      '#theme' => 'custom_taxonomy_block_template', // Theme hook for the block content.
      '#terms' => $terms, // Pass loaded terms to the template.
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    // Call parent blockForm method to build the basic block configuration form.
    $form = parent::blockForm($form, $form_state);

    // Retrieve block configuration.
    $config = $this->getConfiguration();

    // Load all vocabularies.
    $vocabularies = \Drupal\taxonomy\Entity\Vocabulary::loadMultiple();
    // Prepare vocabulary options for select list.
    $options = [];
    foreach ($vocabularies as $vid => $vocabulary) {
      $options[$vid] = $vocabulary->label();
    }

    // Add vocabulary select field to the block configuration form.
    $form['vid'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Vocabulary'),
      '#default_value' => isset($config['vid']) ? $config['vid'] : '', // Set default value from block configuration.
      '#options' => $options, // Provide vocabulary options.
      '#required' => TRUE, // Make this field required.
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Store the submitted vocabulary id in block configuration.
    $this->configuration['vid'] = $form_state->getValue('vid');
  }

}
