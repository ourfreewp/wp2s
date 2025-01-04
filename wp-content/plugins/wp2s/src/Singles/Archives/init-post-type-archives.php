<?php
// Path: wp-content/plugins/wp2s/Singles/Archives/init-post-type-archives.php

namespace WP2S\Singles\Archives\PostTypeArchives;

use WP_Post;

class Controller
{

    private $textdomain   = 'wp2s';
    private $archive_type = 'wp2s_archive';
    private $action_hook  = 'wp2s_generate_archive_post';

    public function __construct()
    {
        add_action('admin_bar_menu', [$this, 'add_trigger_archive_link_to_admin_bar'], 100);
        $this->schedule_async_generate_archive_posts();
    }

    // Schedule asynchronous archive post generation
    public function schedule_async_generate_archive_posts()
    {
        add_action('admin_init', function () {
            if (
                isset($_GET['trigger_archive_generation']) &&
                isset($_GET['post_type']) &&
                $_GET['post_type'] === $this->archive_type
            ) {
                $this->generate_archive_posts();
            }
        });
    }

    // Get all post types with has_archive enabled (exclude those with has_archive set to false)
    public function get_post_types_with_archives()
    {
        $post_types = get_post_types(['public' => true], 'objects');
        $archive_paths = [];

        foreach ($post_types as $type) {
            // Skip post types where has_archive is explicitly false
            if ($type->has_archive !== false) {
                // Use post type if has_archive is true, otherwise use the string (custom path)
                $archive_slug = ($type->has_archive === true) ? $type->name : $type->has_archive;
                $archive_paths[$type->name] = $archive_slug;
            }
        }

        // Apply a filter to allow further customization if needed
        return apply_filters('wp2s_filter_post_types_with_archives', $archive_paths);
    }

    public function add_trigger_archive_link_to_admin_bar($admin_bar)
    {
        if ($this->is_wp2s_archive_page()) {
            $admin_bar->add_node([
                'id'     => 'wp2s_trigger_archive_generation',
                'parent' => 'wp2s_async_action',
                'title'  => 'Generate Archives',
                'href'   => admin_url('edit.php?post_type=' . $this->archive_type . '&trigger_archive_generation=true'),
                'meta'   => [
                    'title' => __('Trigger Archive Generation', $this->textdomain),
                ],
            ]);
        }
    }

    private function is_wp2s_archive_page()
    {
        return (is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === $this->archive_type);
    }

    // Generate and upsert archive posts
    public function generate_archive_posts()
    {
        $archive_paths = $this->get_post_types_with_archives();

        foreach ($archive_paths as $post_type => $archive_path) {
            $this->upsert_post($post_type, $archive_path);
        }

        $this->clean_up();
    }

    // Upsert (Create/Update) individual archive post
    public function upsert_post($post_type, $archive_path)
    {
        $existing_post = get_posts([
            'post_type'   => 'page',
            'name'        => $archive_path,
            'numberposts' => 1,
            'post_status' => 'any',
        ]);

        $existing_post = $existing_post[0] ?? null;

        if ($existing_post instanceof WP_Post) {
            $this->update_post($existing_post->ID, $post_type, $archive_path);
        } else {
            $this->create_post($post_type, $archive_path);
        }
    }

    // Create new archive post
    public function create_post($post_type, $archive_path)
    {
        wp_insert_post([
            'post_type'    => $this->archive_type,
            'post_name'    => $archive_path,
            'post_title'   => ucfirst($archive_path),
            'post_status'  => 'publish',
            'post_content' => '',
        ]);
    }

    // Update existing archive post
    public function update_post($post_id, $post_type, $archive_path)
    {
        wp_update_post([
            'ID'          => $post_id,
            'post_type'   => $this->archive_type,
            'post_name'   => $archive_path,
            'post_status' => 'publish',
        ]);
    }

    // Clean up orphaned archive posts by setting to draft
    public function clean_up()
    {
        $existing_posts = $this->get_posts();
        $archives       = $this->get_post_types_with_archives();

        foreach ($existing_posts as $post) {
            if (!in_array($post->post_name, $archives)) {
                wp_update_post([
                    'ID'          => $post->ID,
                    'post_status' => 'draft'
                ]);
            }
        }
    }

    // Get all archive posts of the custom archive type
    public function get_posts()
    {
        return get_posts([
            'post_type'   => $this->archive_type,
            'numberposts' => -1
        ]);
    }
}

// Instantiate the controller
$controller = new Controller();
