<?php

namespace Drupal\custom_latest_nodes;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Service class for retrieving the latest nodes of a specified category.
 */
class LatestNodesService {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a LatestNodesService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Retrieves the latest nodes of a specified category.
   *
   * @param int $categoryId
   *   The category ID to filter by.
   * @param int $limit
   *   (Optional) The maximum number of nodes to retrieve. Defaults to 5.
   *
   * @return array
   *   An array of node entities.
   */
  public function getLatestNodes($categoryId, $limit = 5) {
    // Fetch the node storage.
    $nodeStorage = $this->entityTypeManager->getStorage('node');

    // Query for nodes of the specified category.
    $query = $nodeStorage->getQuery()
      ->accessCheck(TRUE)
      // Published nodes only.
      ->condition('status', 1)
      // Replace 'page' with your content type.
      ->condition('type', 'page')
      // Sort by creation date, latest first.
      ->sort('created', 'DESC')
      // Limit the number of nodes.
      ->range(0, $limit);

    $current_node = $nodeStorage->create(['type' => 'page']);

    if($categoryId != '') {
      // Check if the field 'field_category' exists.
      if ($current_node->hasField('field_category')) {
        // Replace $categoryId with the category ID you want to filter by.
        $query->condition('field_category', $categoryId);
      }
      else {
        // Field doesn't exist, return an empty array.
        // return [];
      }
    }

    $nids = $query->execute();

    // Load the nodes.
    return $nodeStorage->loadMultiple($nids);
  }

}
