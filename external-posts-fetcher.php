<?php
/**
 * Plugin Name: External WP Posts Importer
 * Plugin URI: https://www.artsunique.de
 * Description: This plugin fetches and imports posts from an external WordPress site.
 * Version: 1.0,1
 * Author: Andreas Burget
 * Author URI: https://www.artsunique.de
 */

function my_plugin_menu() {
    add_options_page('External WP Posts Importer', 'External WP Posts Importer', 'manage_options', 'my-plugin', 'my_plugin_options');
}

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_options() {

    if (!current_user_can('manage_options'))  {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $fetched_posts = get_option('my_plugin_fetched_posts', array());
    ?>
    <div class="wrap my-plugin-container">
        <h1>External WP Posts Importer</h1>

        <?php if (empty($fetched_posts)) { ?>
            <p>No fetched posts found.</p>
        <?php } else { ?>
            <h2>Fetched Posts</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($fetched_posts as $post_id) { ?>
                    <tr>
                        <td><?php echo $post_id; ?></td>
                        <td><?php echo get_the_title($post_id); ?></td>
                        <td>
                            <a href="<?php echo get_edit_post_link($post_id); ?>" class="button my-plugin-button">Edit</a>
                            <form method="post" style="display: inline;">
                                <?php wp_nonce_field('my-plugin-delete-post'); ?>
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                <input type="submit" class="button my-plugin-button" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('my-plugin-delete-all-posts'); ?>
                <input type="hidden" name="action" value="delete_all_posts">
                <input type="submit" class="button button-primary my-plugin-button" value="Delete All Posts">
            </form>
        <?php } ?>

        <h2>Import Posts</h2>
        <form method="post">
            <?php wp_nonce_field('my-plugin-fetch-posts'); ?>
            <p>
                <label>Site URL:</label><br>
                <input type="text" name="site_url" class="regular-text my-plugin-input" required>
            </p>
            <p>
                <label>Number of Posts Per Page:</label><br>
                <input type="number" name="num_posts" class="regular-text my-plugin-input" required>
            </p>
            <p>
                <label>Page Number:</label><br>
                <input type="number" name="page_num" class="regular-text my-plugin-input" required>
            </p>
            <input type="submit" class="button button-primary my-plugin-button" value="Fetch Posts">
        </form>
    </div>



    <?php
}

function my_plugin_enqueue_styles() {
    wp_enqueue_style('my-plugin-styles', plugins_url('fetcher.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'my_plugin_enqueue_styles');

function my_plugin_fetch_posts() {
    if (isset($_POST['site_url']) && isset($_POST['num_posts']) && isset($_POST['page_num']) && check_admin_referer('my-plugin-fetch-posts')) {
        $url = esc_url_raw(sanitize_text_field($_POST['site_url']) . '/wp-json/wp/v2/posts?per_page=' . intval($_POST['num_posts']) . '&page=' . intval($_POST['page_num']) . '&_embed');

        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            add_action('admin_notices', function () use ($response) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Error fetching posts: ' . $response->get_error_message(), 'my-plugin'); ?></p>
                </div>
                <?php
            });
            return;
        }

        $posts = json_decode(wp_remote_retrieve_body($response));
        if (empty($posts)) {
            add_action('admin_notices', function () {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('No posts found at the specified URL.', 'my-plugin'); ?></p>
                </div>
                <?php
            });
            return;
        }

        $imported_posts = 0;
        foreach ($posts as $post) {
            $existing_post = get_page_by_title(sanitize_text_field($post->title->rendered), OBJECT, 'post');
            if ($existing_post) {
                continue; // Skip importing duplicate post
            }

            $author_name = $post->_embedded->author[0]->name;
            $author_id = get_user_by('login', $author_name) ? get_user_by('login', $author_name)->ID : get_current_user_id();
            $new_post_id = wp_insert_post([
                'post_title' => sanitize_text_field($post->title->rendered),
                'post_content' => wp_kses_post($post->content->rendered),
                'post_excerpt' => wp_kses_post($post->excerpt->rendered),
                'post_author' => $author_id,
                'post_status' => 'draft',
                'post_date' => sanitize_text_field($post->date),
                'post_format' => sanitize_text_field($post->format),
            ]);

            if (is_wp_error($new_post_id)) {
                add_action('admin_notices', function () use ($new_post_id) {
                    ?>
                    <div class="notice notice-error">
                        <p><?php _e('Error inserting post: ' . $new_post_id->get_error_message(), 'my-plugin'); ?></p>
                    </div>
                    <?php
                });
                return;
            } else {
                if (isset($post->_embedded->{'wp:featuredmedia'}[0]->source_url)) {
                    $image_url = esc_url_raw($post->_embedded->{'wp:featuredmedia'}[0]->source_url);
                    $image_id = my_plugin_insert_attachment_from_url($image_url);
                    set_post_thumbnail($new_post_id, $image_id);
                }

                if (!empty($post->categories)) {
                    $new_categories = [];
                    foreach ($post->categories as $category_id) {
                        $category_response = wp_remote_get(sanitize_text_field($_POST['site_url']) . '/wp-json/wp/v2/categories/' . intval($category_id));
                        if (is_wp_error($category_response)) {
                            continue;
                        }
                        $category_data = json_decode(wp_remote_retrieve_body($category_response));
                        $new_category_id = wp_create_category(sanitize_text_field($category_data->name));
                        $new_categories[] = $new_category_id;
                    }
                    wp_set_post_categories($new_post_id, $new_categories);
                }

                if (!empty($post->tags)) {
                    $new_tags = [];
                    foreach ($post->tags as $tag_id) {
                        $tag_response = wp_remote_get(sanitize_text_field($_POST['site_url']) . '/wp-json/wp/v2/tags/' . intval($tag_id));
                        if (is_wp_error($tag_response)) {
                            continue;
                        }
                        $tag_data = json_decode(wp_remote_retrieve_body($tag_response));
                        if (!term_exists(sanitize_text_field($tag_data->name), 'post_tag')) {
                            wp_insert_term(sanitize_text_field($tag_data->name), 'post_tag');
                        }
                        $new_tags[] = sanitize_text_field($tag_data->name);
                    }
                    wp_set_post_tags($new_post_id, $new_tags);
                }

                // Save the imported post ID
                $fetched_posts = get_option('my_plugin_fetched_posts', array());
                $fetched_posts[] = $new_post_id;
                update_option('my_plugin_fetched_posts', $fetched_posts);

                $imported_posts++;
            }
        }

        add_action('admin_notices', function () use ($imported_posts) {
            ?>
            <div class="notice notice-success">
                <p><?php _e('Posts imported successfully: ' . $imported_posts, 'my-plugin'); ?></p>
            </div>
            <?php
        });
    }
}

add_action('admin_init', 'my_plugin_fetch_posts');

function my_plugin_delete_post() {
    if (isset($_POST['post_id']) && check_admin_referer('my-plugin-delete-post')) {
        $post_id = intval($_POST['post_id']);
        wp_delete_post($post_id, true);

        // Remove the post ID from fetched posts
        $fetched_posts = get_option('my_plugin_fetched_posts', array());
        $index = array_search($post_id, $fetched_posts);
        if ($index !== false) {
            unset($fetched_posts[$index]);
            update_option('my_plugin_fetched_posts', $fetched_posts);
        }

        add_action('admin_notices', function () {
            ?>
            <div class="notice notice-success">
                <p><?php _e('Post deleted successfully.', 'my-plugin'); ?></p>
            </div>
            <?php
        });
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_all_posts' && check_admin_referer('my-plugin-delete-all-posts')) {
        $fetched_posts = get_option('my_plugin_fetched_posts', array());
        foreach ($fetched_posts as $post_id) {
            wp_delete_post($post_id, true);
        }
        update_option('my_plugin_fetched_posts', array());

        add_action('admin_notices', function () {
            ?>
            <div class="notice notice-success">
                <p><?php _e('All fetched posts deleted successfully.', 'my-plugin'); ?></p>
            </div>
            <?php
        });
    }
}

add_action('admin_init', 'my_plugin_delete_post');

function my_plugin_insert_attachment_from_url($url) {
    $file_array = [];
    $file_array['name'] = basename(sanitize_text_field($url));
    $file_array['tmp_name'] = download_url($url);
    if (is_wp_error($file_array['tmp_name'])) {
        @unlink($file_array['tmp_name']);
        return $file_array['tmp_name'];
    }
    $id = media_handle_sideload($file_array, 0);
    if (is_wp_error($id)) {
        @unlink($file_array['tmp_name']);
        return $id;
    }
    return $id;
}
