<?php

namespace Drupal\custom_latest_nodes\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\custom_latest_nodes\LatestNodesService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Latest Nodes' block.
 *
 * @Block(
 *   id = "custom_latest_nodes_block",
 *   admin_label = @Translation("Latest Nodes"),
 * )
 */
class LatestNodesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $latestNodesService;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, LatestNodesService $latestNodesService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->latestNodesService = $latestNodesService;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('custom_latest_nodes.latest_nodes_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    // Get the current node.
    $currentNode = \Drupal::routeMatch()->getParameter('node');
    if ($currentNode) {
      // Get the category from the current node.
      $categoryId = $currentNode->get('field_category')->target_id;

      // Get the latest nodes related to the category.
      $latestNodes = $this->latestNodesService->getLatestNodes($categoryId, 5);

      // Build the render array for the latest nodes.
      foreach ($latestNodes as $node) {
        $build[] = [
          '#type' => 'markup',
        // Display node title. Modify as needed.
          '#markup' => $node->getTitle(),
        ];
      }
    }

    return $build;
  }

}
