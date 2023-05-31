<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 11L11 3.72792C11 3.26184 11.1851 2.81485 11.5147 2.48528C12.5387 1.46129 14.2923 2.04581 14.4971 3.47939L16 10L17.5081 3.6723C17.7769 2.75853 18.7501 2.25003 19.6537 2.55123C20.4313 2.81044 20.9088 3.59248 20.7842 4.40265L19 16" 
stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M19 16C18.4643 20 15.6421 22 11.5 22C7.35786 22 4 20 4 16" stroke="currentColor" stroke-width="2"></path><path d="M4 16V12C4 10.8954 4.89543 10 6 10C7.10457 10 8 10.8954 8 12M12 13V11C12 9.89543 11.1046 9 10 9C8.89543 9 8 9.89543 8 11V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" s
troke-linejoin="round"></path><path d="M13.6923 17H11C9.89543 17 9 16.1046 9 15C9 13.8954 9.89543 13 11 13L15 13C17.2091 13 19.5 15 18.5 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="height="200px; width="200px;"></path></svg>

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
