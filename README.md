<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000" width="200" height="200">
  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.29-11.71L14 9l-4 4-4-4 1.41-1.41L10 10.17V3h4v7.17l2.29-2.29L14 9l-1.71 1.71z"/>
</svg>

# External WordPress Posts Importer

> Import posts from an external WordPress site into your own WordPress installation.

## Description

External WP Posts Importer is a WordPress plugin that allows you to fetch and import posts from an external WordPress site into your own WordPress installation. It provides a simple and efficient way to migrate content or syndicate posts from different WordPress sites.

The plugin retrieves the post's title, content, excerpt, author, post format, publication date, categories, tags, featured image, and comments. It also offers pagination support for efficient fetching of a large number of posts.

## Features

- Fetch and import posts from an external WordPress site
- Import post title, content, excerpt, author, post format, publication date, categories, tags, featured image, and comments
- Efficient fetching with pagination support
- Intuitive interface in the WordPress admin area for configuration and management

## Installation

1. Download the latest release of the plugin from the [Releases](https://github.com/artsunique/external-wp-posts-importer/releases) page.
2. Upload the plugin folder to the `/wp-content/plugins/` directory of your WordPress installation.
3. Activate the plugin through the 'Plugins' menu in the WordPress admin area.
4. Go to the 'External WP Posts Importer' settings page under the 'Settings' menu in the WordPress admin area to configure the plugin.

## Usage

1. In the plugin settings page, enter the URL of the external WordPress site from which you want to fetch the posts.
2. Specify the number of posts to fetch per page, the page number to start fetching, and the post type (post, page, or custom post type).
3. Click the 'Fetch Posts' button to initiate the import process.
4. The fetched posts will be listed in the plugin settings page, along with the option to edit or delete each post individually.
5. To delete a fetched post, click the 'Delete' button next to the post in the list.
6. To edit a fetched post, click the 'Edit' button next to the post in the list, which will take you to the post editor in the WordPress admin area.

**Note:** The fetched posts are imported as drafts. You can review and modify the imported posts as needed before publishing them.

## Requirements

- WordPress version 4.7 or higher.
- PHP version 5.6 or higher.

## Changelog

### 1.1.0
Added support for custom post types in the import process.
Improved performance and efficiency of post fetching and importing.
Enhanced error handling and error messages for a better user experience.

### 1.0.1
Fixed issues with duplicate post import.
Added the option to delete all fetched posts at once.

### 1.0
Initial release.

## Contributing

Contributions are welcome! If you encounter any issues or have suggestions for improvements, please open an issue or submit a pull request on the [GitHub repository](https://github.com/your-username/external-wp-posts-importer).

## License

This plugin is licensed under the [MIT License](https://opensource.org/licenses/MIT).

## Credits

External WP Posts Importer was developed by [Andreas Burget](https://artsunique.de).
