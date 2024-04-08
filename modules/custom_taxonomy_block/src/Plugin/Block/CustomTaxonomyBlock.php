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
    $config = $this->getConfiguration();
    $vid = isset($config['vid']) ? $config['vid'] : '';

    $terms = [];
    if (!empty($vid)) {
      $terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadTree($vid);
    }

    return [
      '#theme' => 'custom_taxonomy_block_template',
      '#terms' => $terms,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $vocabularies = \Drupal\taxonomy\Entity\Vocabulary::loadMultiple();
    $options = [];
    foreach ($vocabularies as $vid => $vocabulary) {
      $options[$vid] = $vocabulary->label();
    }

    $form['vid'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Vocabulary'),
      '#default_value' => isset($config['vid']) ? $config['vid'] : '',
      '#options' => $options,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['vid'] = $form_state->getValue('vid');
  }

}
