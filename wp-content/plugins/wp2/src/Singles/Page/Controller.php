<?php

namespace WP2\Singles;

class Controller
{
    /**
     * Initialize the reserver by registering hooks.
     */
    public function __construct()
    {
        add_action('init', [$this, 'register_reserved_status']);
        add_action('init', [$this, 'reserve_posts']);
    }

    /**
     * Register the 'reserved' post status.
     */
    public function register_reserved_status()
    {
        register_post_status('reserved', [
            'label'                     => _x('Reserved', 'post'),
            'label_count'               => _n_noop('Reserved <span class="count">(%s)</span>', 'Reserved <span class="count">(%s)</span>'),
            'exclude_from_search'       => false,
            'public'                    => true,
            'publicly_queryable'        => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
        ]);
    }

    /**
     * Reserve predefined posts/pages by setting the 'reserved' status.
     */
    public function reserve_posts()
    {
        $posts = $this->prepare_posts();

        foreach ($posts as $post) {
            $this->upsert_reserved_post($post);
        }
    }

    /**
     * Prepare a list of posts to reserve.
     */
    private function prepare_posts()
    {
        $pages = array_map(function ($page) {
            $page['type'] = 'page';
            return $page;
        }, $this->reserved_pages());

        return $pages;
    }

    /**
     * Insert or update a reserved post.
     */
    private function upsert_reserved_post($post)
    {
        $existing_post = get_page_by_path($post['name'], OBJECT, $post['type']);

        $post_data = [
            'post_title'   => $post['title'],
            'post_name'    => $post['name'],
            'post_excerpt' => $post['excerpt'],
            'post_author'  => get_current_user_id(),
            'post_status'  => 'reserved',
            'post_type'    => $post['type'],
        ];

        if ($existing_post) {
            $post_data['ID'] = $existing_post->ID;
            wp_update_post($post_data);
        } else {
            $inserted = wp_insert_post($post_data, true);

            if (is_wp_error($inserted)) {
                error_log('Failed to insert reserved post: ' . $inserted->get_error_message());
            }
        }
    }

    /**
     * Predefined list of reserved pages.
     */
    private function reserved_pages()
    {
        return [
            ['title' => 'About', 'name' => 'about', 'excerpt' => 'Information about the website.'],
            ['title' => 'Contact', 'name' => 'contact', 'excerpt' => 'Get in touch with us.'],
            ['title' => 'Privacy', 'name' => 'privacy', 'excerpt' => 'Website privacy policy.'],
            ['title' => 'Terms', 'name' => 'terms', 'excerpt' => 'Website terms of service.'],
            ['title' => 'Support', 'name' => 'support', 'excerpt' => 'Help and support resources.'],
            ['title' => 'Error', 'name' => 'error', 'excerpt' => 'Generic error page.'],
            ['title' => 'Dashboard', 'name' => 'dashboard', 'excerpt' => 'User account dashboard.'],
            ['title' => 'Auth', 'name' => 'auth', 'excerpt' => 'Authentication portal.'],
            ['title' => '404', 'name' => '404', 'excerpt' => 'Page not found error.'],
            ['title' => 'Search', 'name' => 'search', 'excerpt' => 'Search the website.'],
        ];
    }
}
