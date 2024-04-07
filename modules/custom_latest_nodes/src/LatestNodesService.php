<?php

namespace Drupal\custom_latest_nodes;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class LatestNodesService {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public function getLatestNodes($categoryId, $limit = 5) {
    // Fetch the node storage.
    $nodeStorage = $this->entityTypeManager->getStorage('node');

    // Query for nodes of the specified category.
    $query = $nodeStorage->getQuery()
      ->accessCheck(TRUE)
      ->condition('status', 1) // Published nodes only.
      ->condition('type', 'page') // Replace with your content type.
      ->sort('created', 'DESC') // Sort by creation date, latest first.
      ->range(0, $limit); // Limit the number of nodes.

    $sample_node = $nodeStorage->create(['type' => 'page']);

    // Check if the field 'field_category' exists.
    if ($sample_node->hasField('field_category')) {
      // Replace $categoryId with the category ID you want to filter by.
      $query->condition('field_category', $categoryId);
    } else {
      // Field doesn't exist, return null or handle the case as needed.
      return [];
    }

    $nids = $query->execute();

    // Load the nodes.
    return $nodeStorage->loadMultiple($nids);
  }
}
