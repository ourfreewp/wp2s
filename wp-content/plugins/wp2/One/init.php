<?php
/**
 * Plugin Name: WP2 One - Admin, Auth, Profiles
 * Network: true
 */

namespace WP2\One;

// Initialize classes
new Admin();
new Auth();

// =======================
// Admin UI & Restrictions
// =======================
class Admin
{
    public function __construct()
    {

        add_filter('rwmb_meta_boxes', [$this, 'register_content_permissions_taxonomies'], 999);
    }

    private function is_admin_user()
    {
        $user = wp_get_current_user();
        return in_array('administrator', $user->roles) || is_super_admin($user->ID);
    }

    public function register_content_permissions_taxonomies($meta_boxes)
    {
        $text_domain = sanitize_title(get_bloginfo('name'));

        $meta_boxes[] = [
            'title'      => __('Permissions', $text_domain),
            'id'         => 'permissions',
            'post_types' => ['post', 'page', 'coda-doc'],
            'context'    => 'side',
            'fields'     => [
                [
                    'name'       => __('User Roles', $text_domain),
                    'id'         => 'user_roles',
                    'type'       => 'taxonomy',
                    'taxonomy'   => ['user_roles'],
                    'field_type' => 'checkbox_tree',
                ],
                [
                    'name'       => __('User Levels', $text_domain),
                    'id'         => 'user_levels',
                    'type'       => 'taxonomy',
                    'taxonomy'   => ['user_levels'],
                    'field_type' => 'checkbox_tree',
                ],
            ],
        ];

        return $meta_boxes;
    }

}

// =======================
// Authentication Redirect
// =======================
class Auth
{
    public function __construct()
    {
        add_action('wo_before_authorize_method', [$this, 'custom_oauth_login_redirect']);
    }

    public function custom_oauth_login_redirect()
    {
        if (!is_user_logged_in()) {
            $current_url  = esc_url_raw(home_url(add_query_arg(null, null)));
            $login_url    = site_url('/account');
            $redirect_url = add_query_arg('redirect_to', urlencode($current_url), $login_url);

            wp_safe_redirect(esc_url_raw($redirect_url));
            exit;
        }
    }
}
