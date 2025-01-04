<?php
// Path: wp-content/plugins/wp2/Bio/Controller.php

namespace WP2\Bio;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_BIO_PATH', plugin_dir_path(__FILE__));
define('WP2_BIO_URL', plugin_dir_url(__FILE__));
define('WP2_BIO_VERSION', '1.0.0');

class Controller
{

    public function register_profile_post_type()
    {
        $text_domain = sanitize_title(get_bloginfo('name'));

        $labels = [
            'name' => __('Public Profiles', $text_domain),
            'singular_name' => __('Public Profile', $text_domain),
            'add_new' => __('Add New', $text_domain),
            'add_new_item' => __('Add New Public Profile', $text_domain),
            'edit_item' => __('Edit Public Profile', $text_domain),
            'new_item' => __('New Public Profile', $text_domain),
            'view_item' => __('View Public Profile', $text_domain),
            'view_items' => __('View Public Profiles', $text_domain),
            'search_items' => __('Search Public Profiles', $text_domain),
            'not_found' => __('No Public Profiles found', $text_domain),
            'not_found_in_trash' => __('No Public Profiles found in Trash', $text_domain),
            'all_items' => __('All Public Profiles', $text_domain),
        ];

        $args = [
            'label' => esc_html__('Profiles', $text_domain),
            'labels' => $labels,
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['custom-fields', 'author'],
            'rewrite' => ['slug' => 'p', 'with_front' => false],
            'menu_icon' => 'dashicons-admin-users',
        ];

        register_post_type('public_profile', $args);
    }


    public function register_profile_taxonomy()
    {
        register_taxonomy('profile-type', ['public_profile'], [
            'label' => esc_html__('Types', 'oddnews'),
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
        ]);
    }

    public function add_profile_meta_boxes($meta_boxes)
    {
        $text_domain = sanitize_title(get_bloginfo('name'));

        $meta_boxes[] = [
            'title'  => __('Profile Settings', $text_domain),
            'id'     => 'profile-settings',
            'type'   => 'user',
            'fields' => [
                [
                    'name'       => __('Profile', $text_domain),
                    'id'         => 'profile',
                    'type'       => 'post',
                    'post_type'  => ['public_profile'],
                    'field_type' => 'select_advanced',
                ],
                [
                    'name'       => __('Link', 'oddnewsshow'),
                    'id'         => 'user_profile_links',
                    'type'       => 'url',
                    'clone'      => true,
                    'sort_clone' => true,
                ],
            ],
        ];

        $meta_boxes[] = [
            'id' => 'public-profile',
            'title' => __('Public Profile', $text_domain),
            'type' => 'user',
            'fields' => [
                ['type' => 'custom_html', 'callback' => [$this, 'user_fields_callback']],
                ['type' => 'divider'],
            ],
        ];

        return $meta_boxes;
    }

    public function wp_login_check($user)
    {
        return $this->create_profile_post($user->ID) ? true : false;
    }

    public function profile_update_check($user_id)
    {
        return $this->create_profile_post($user_id) ? true : false;
    }

    public function create_profile_post($user_id)
    {
        $user = get_user_by('id', $user_id);
        $profile_slug = '_' . sanitize_title($user->user_login);
        $profile_post = get_page_by_path($profile_slug, OBJECT, 'public_profile');

        $post_data = [
            'post_title' => $user->user_login,
            'post_content' => '',
            'post_status' => 'private',
            'post_author' => $user_id,
            'post_type' => 'public_profile',
            'post_name' => $profile_slug,
        ];

        if ($profile_post) {
            $post_data['ID'] = $profile_post->ID;
            $profile_post = wp_update_post($post_data);
        } else {
            $profile_post = wp_insert_post($post_data);
        }

        update_user_meta($user_id, 'profile-post', $profile_post);
        return $profile_post;
    }

    public function user_fields_callback()
    {
        $user_id = $_GET['user_id'];
        $profile_post = $this->create_profile_post($user_id);
        $text_domain = sanitize_title(get_bloginfo('name'));

        if ($profile_post) {
            return '<p>' . __('Profile post created', $text_domain) . '</p>';
        }
        return '<p>' . __('Profile post not created', $text_domain) . '</p>';
    }
}