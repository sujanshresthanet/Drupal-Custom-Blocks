# Custom Drupal 9/10 Module: Node Listing

This custom Drupal 9 module provides functionality to list nodes based on certain criteria. It offers a flexible way to display nodes through a configurable block or page.

## Features

- List nodes based on content types, taxonomy terms, or other custom filters.
- Configurable block for easy placement in different regions of your site.
- Page display for standalone node listing.

## Installation

1. Clone this repository or download the ZIP file and extract it into your Drupal installation's modules directory (usually `modules/custom` or `modules`).
2. Enable the module either via the Drupal admin interface or by using Drush: `drush en nodes_listing`.

## Usage

### Block Placement

1. Navigate to the Block Layout configuration page (`/admin/structure/block`).
2. Find the block titled "Current Node Type Node Listing" and click on the "Place block" button.
3. Choose the region where you want to place the block and configure the block settings as needed.
4. Save the block configuration.

### Page Display

1. Create a new page or edit an existing one where you want to display the node listing.
2. Add a new block or content section to the page.
3. Select the "Current Node Type Node Listing" block or shortcode and configure it accordingly.
4. Save the changes to the page.


## Requirements

- Drupal <= 9.x

## Contributing

Contributions are welcome! Please fork this repository, make your changes, and submit a pull request.

## License

This project is licensed under the [GNU General Public License v2.0](LICENSE).
