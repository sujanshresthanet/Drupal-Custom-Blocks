<?php

namespace Drupal\nodes_listing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Provides a 'Custom Node Block' block.
 *
 * @Block(
 *   id = "nodes_type_listing",
 *   admin_label = @Translation("Current Node Type Node Listing"),
 *   category = @Translation("Custom Blocks")
 * )
 */
class CurrentNodeTypeNodeListingBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The RouteMatch service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The EntityTypeManager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new CustomNodeBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The RouteMatch service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The EntityTypeManager service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteMatchInterface $route_match,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    // Get the current node.
    $node = $this->routeMatch->getParameter('node');

    // Check if the current route is a node page and if a node exists.
    if ($node && $node instanceof \Drupal\node\NodeInterface) {
      // Get the node type.
      $node_type = $node->getType();
      $node_count = $this->configuration['node_count'] ?? 5;

      // Load 5 nodes of the same type as the current node.
      $query = $this->entityTypeManager->getStorage('node')->getQuery()
        ->accessCheck(TRUE)
        ->condition('type', $node_type)
        ->condition('status', 1)
        ->condition('nid', $node->id(), '<>') // Exclude current node.
        ->range(0, $node_count)
        ->sort('created', 'DESC');
      $node_ids = $query->execute();

      // Load the nodes.
      $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($node_ids);

      // Build the render array for the nodes.
      $items = [];
      foreach ($nodes as $node) {
        $items[] = [
          '#type' => 'link',
          '#title' => $node->getTitle(),
          '#url' => $node->toUrl(),
        ];
      }

      // Add the nodes to the block content.
      $build['content'] = [
        '#theme' => 'item_list',
        '#items' => $items,
      ];

    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $form['node_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of nodes to display'),
      '#default_value' => $this->configuration['node_count'] ?? 5,
      '#min' => 1,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['node_count'] = $form_state->getValue('node_count');
  }

}
