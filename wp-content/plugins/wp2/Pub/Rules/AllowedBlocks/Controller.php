<?php

namespace WP2\Singles\BlockTypes;

class Controller
{
    public function __construct()
    {
        add_filter('allowed_block_types_all', [$this, 'filter_allowed_blocks'], 10, 2);
        add_filter('mb_settings_pages', [$this, 'register_settings_page'], 10, 1);
        add_filter('rwmb_meta_boxes', [$this, 'register_meta_boxes'], 10, 1);
    }

    /**
     * Filters allowed blocks based on user role, post type, and admin settings.
     *
     * @param array $allowed_block_types
     * @param object $editor_context
     * @return array
     */
    public function filter_allowed_blocks($allowed_block_types, $editor_context)
    {
        $user = wp_get_current_user();

        // Admin override for block management
        $block_admins = rwmb_meta('block_admins', ['object_type' => 'setting'], 'allowed-blocks-settings');
        if (in_array($user->ID, $block_admins)) {
            return $allowed_block_types;
        }

        // Allow all for pages and site editor
        if (
            $editor_context->post->post_type === 'page' ||
            $editor_context->name === 'core/edit-site'
        ) {
            return $allowed_block_types;
        }

        // Deny all blocks for subscribers
        if (in_array('subscriber', $user->roles)) {
            return [];
        }

        // Define common allowed blocks
        $common_blocks = [
            'core/group',
            'core/embed',
            'core/heading',
            'core/list',
            'core/list-item',
            'core/missing',
            'core/paragraph',
            'core/preformatted',
            'core/pullquote',
            'core/quote',
            'core/separator',
            'core/table',
            'custom/image',
        ];

        // Add custom blocks for specific post types
        if (in_array($editor_context->post->post_type, ['article', 'forecasts'])) {
            $common_blocks[] = 'otw/outstream-player';
        }

        return $common_blocks;
    }

    /**
     * Registers a custom settings page for block management.
     *
     * @param array $settings_pages
     * @return array
     */
    public function register_settings_page($settings_pages)
    {
        $settings_pages[] = [
            'menu_title' => __('Allowed Blocks', 'oddnewsshow'),
            'id' => 'allowed-blocks-settings',
            'position' => 2,
            'parent' => 'options-general.php',
            'capability' => 'publish_pages',
            'style' => 'no-boxes',
            'columns' => 1,
            'icon_url' => 'dashicons-admin-generic',
        ];

        return $settings_pages;
    }

    /**
     * Registers meta boxes for managing allowed and denied blocks.
     *
     * @param array $meta_boxes
     * @return array
     */
    public function register_meta_boxes($meta_boxes)
    {
        $setting_page = 'allowed-blocks-settings';
        $wp_roles = wp_roles();
        $roles = $wp_roles->roles;

        $tabs = ['general' => ['label' => 'General', 'icon' => '']];

        // Build tabs for each role
        foreach ($roles as $role => $role_data) {
            $tabs[$role] = ['label' => $role_data['name'], 'icon' => ''];
        }

        $fields = $this->generate_role_fields($roles);

        $fields[] = [
            'id' => 'block_admins',
            'name' => 'Block Admins',
            'type' => 'user',
            'tab' => 'general',
            'multiple' => true,
            'columns' => 12,
        ];

        $meta_boxes[] = [
            'settings_pages' => [$setting_page],
            'tabs' => $tabs,
            'fields' => $fields,
        ];

        return $meta_boxes;
    }

    /**
     * Generates block allow/deny fields for each role.
     *
     * @param array $roles
     * @return array
     */
    private function generate_role_fields($roles)
    {
        $fields = [];
        foreach ($roles as $role => $role_data) {
            $fields[] = [
                'id' => 'allowed_blocks_' . $role,
                'name' => 'Allowed Blocks',
                'type' => 'text_list',
                'tab' => $role,
                'clone' => true,
                'columns' => 12,
                'options' => ['core' => 'Namespace', 'group' => 'Block'],
            ];

            $fields[] = ['type' => 'divider', 'columns' => 12, 'tab' => $role];

            $fields[] = [
                'id' => 'denied_blocks_' . $role,
                'name' => 'Denied Blocks',
                'type' => 'text_list',
                'tab' => $role,
                'clone' => true,
                'columns' => 12,
                'options' => ['core' => 'Namespace', 'group' => 'Block'],
            ];
        }

        return $fields;
    }
}