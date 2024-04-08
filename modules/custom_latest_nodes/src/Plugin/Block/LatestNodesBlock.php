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

  /**
   * Constructs a LatestNodesBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\custom_latest_nodes\LatestNodesService $latestNodesService
   *   The latest nodes service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LatestNodesService $latestNodesService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->latestNodesService = $latestNodesService;
  }

  /**
   * {@inheritdoc}
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
   * Builds and returns the renderable array for the block.
   *
   * @return array
   *   A renderable array representing the content of the block.
   */
  public function build() {
    $build = [];

    // Get the current node.
    $currentNode = \Drupal::routeMatch()->getParameter('node');
    if ($currentNode) {
      $categoryId = '';

      // Check if the current node has 'field_category' field
      if($currentNode->hasField('field_category')) {
        // Get the category from the current node.
        $categoryId = $currentNode->get('field_category')->target_id;
      }

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
