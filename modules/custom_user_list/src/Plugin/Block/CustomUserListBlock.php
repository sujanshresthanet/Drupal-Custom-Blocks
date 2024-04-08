<?php

namespace Drupal\custom_user_list\Plugin\Block;

use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Custom User List' Block.
 *
 * @Block(
 *   id = "custom_user_list_block",
 *   admin_label = @Translation("Custom User List"),
 *   category = @Translation("Custom Blocks")
 * )
 */
class CustomUserListBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   * Builds and returns the renderable array for the block.
   */
  public function build() {
    // Retrieve configuration values for the block.
    $config = $this->getConfiguration();
    $roles = $config['roles'] ?? [];

    // Query users based on selected roles.
    $query = \Drupal::entityQuery('user');
    $query->accessCheck(TRUE); // Ensure access check for current user.
    if (!empty($roles)) {
      $query->condition('roles', $roles, 'IN');
    }
    $uids = $query->execute();
    $users = \Drupal::entityTypeManager()->getStorage('user')->loadMultiple($uids);

    // Prepare user names to be displayed.
    $items = [];
    foreach ($users as $user) {
      $items[] = $user->getAccountName();
    }

    // Return renderable array containing user names.
    return [
      '#theme' => 'item_list',
      '#items' => $items,
    ];
  }

  /**
   * {@inheritdoc}
   * Defines the form for configuring block settings.
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    // Retrieve configuration values for the block.
    $config = $this->getConfiguration();
    $roles = $config['roles'] ?? [];

    // Fetch available roles.
    $options = [];
    $roleStorage = \Drupal::entityTypeManager()->getStorage('user_role');
    foreach ($roleStorage->loadMultiple() as $role) {
      $options[$role->id()] = $role->label();
    }

    // Define form elements for selecting roles.
    $form['roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select Roles'),
      '#options' => $options,
      '#default_value' => $roles,
      '#description' => $this->t('Select the roles whose users should be listed.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   * Processes the block configuration form submission.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save selected roles in block configuration.
    $this->setConfigurationValue('roles', array_filter($form_state->getValue('roles')));
  }
}
