# Dynamic Users Listing Module for Drupal 9/10, Based on User Roles

This Drupal 9/10 module allows you to create a block that lists users dynamically based on selected roles. It provides a user-friendly configuration interface to place the block on your site.

## Features

- Allows administrators to select specific roles from which users will be listed.
- Provides a configurable block that can be easily placed on any page.
- Dynamic user listing ensures that only users with the selected roles are displayed.

## Requirements

- Drupal 9.x or 10.x
- Permissions to install and configure modules

## Installation

1. Download or clone this repository into the `modules` directory of your Drupal installation.
2. Enable the "Dynamic Users Listing" module through the Drupal administration interface (`/admin/modules`).

## Configuration

1. Navigate to the block layout configuration page (`/admin/structure/block`).
2. Click on "Place block".
3. Look for the "Custom User List" block in the list of available blocks.
4. Place the block in the desired region.
5. Configure the block by selecting the roles from which users should be listed.
6. Save the configuration.

## Usage

Once the block is configured and placed, it will automatically list users based on the selected roles. Users with the chosen roles will be dynamically displayed wherever the block is placed on your site.

## Contributing

Contributions are welcome! If you encounter any issues or have suggestions for improvements, please [open an issue](https://github.com/sujanshresthanet/Drupal-Custom-Blocks/issues) or [submit a pull request](https://github.com/sujanshresthanet/Drupal-Custom-Blocks/pulls).

## License

This project is licensed under the [GNU General Public License v2.0](LICENSE).
