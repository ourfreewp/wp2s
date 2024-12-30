<?php

namespace WP2\Studio\Studios;

class Controller
{
    private $namespace = 'wp2';
    private $post_type = 'wp2-studio';
    private $singular = 'Studio';
    private $plural = 'Studios';

    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('init', [$this, 'initialize_studios']);
        add_action('wp2_studio_scan', [$this, 'process_studios']);
    }

    /**
     * Registers the custom post type for WP2 Studio.
     */
    public function register_post_type()
    {
        $labels = $this->generate_labels($this->singular, $this->plural);
        
        $args = [
            'label'               => $this->plural,
            'labels'              => $labels,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_rest'        => true,
            'rest_base'           => strtolower($this->plural),
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-admin-customizer',
            'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields', 'revisions'],
            'has_archive'         => strtolower($this->plural),
            'rewrite'             => ['slug' => strtolower($this->singular), 'with_front' => false],
        ];

        register_post_type($this->post_type, $args);
    }

    /**
     * Registers taxonomies for WP2 Studio.
     */
    public function register_taxonomies()
    {
        $taxonomies = [
            'feature'    => ['singular' => 'Feature', 'plural' => 'Features'],
            'category'   => ['singular' => 'Category', 'plural' => 'Categories'],
            'type'       => ['singular' => 'Type', 'plural' => 'Types'],
        ];

        foreach ($taxonomies as $slug => $names) {
            $taxonomy = $this->post_type . '_' . $slug;
            $labels = $this->generate_labels($names['singular'], $names['plural']);

            register_taxonomy($taxonomy, $this->post_type, [
                'labels'            => $labels,
                'public'            => true,
                'show_in_rest'      => true,
                'hierarchical'      => false,
                'show_ui'           => true,
                'show_admin_column' => true,
            ]);
        }
    }

    /**
     * Initialize BlockStudio Instances.
     */
    public function initialize_studios()
    {
        $folders = $this->get_studio_folders();
        
        if (!class_exists('\Blockstudio\Build')) {
            error_log('Blockstudio\Build class not found.');
            return;
        }

        foreach ($folders as $folder) {
            \Blockstudio\Build::init(['dir' => $folder]);
        }
    }

    /**
     * Process studios asynchronously.
     */
    public function process_studios()
    {
        $definitions = $this->get_studio_definitions();

        if (empty($definitions)) {
            error_log('No studio definitions found.');
            return;
        }

        foreach ($definitions as $definition) {
            $this->upsert_studio_post($definition);
            $this->generate_directory($definition);
        }

        error_log('Studios processed successfully.');
    }

    /**
     * Retrieve studio definitions from a JSON file.
     */
    private function get_studio_definitions()
    {
        $definitions_path = WP2_EXT_BLOCKSTUDIO_PATH . '/src/Definitions/definitions.json';

        if (!file_exists($definitions_path)) {
            return [];
        }

        $content = file_get_contents($definitions_path);
        return json_decode($content, true) ?? [];
    }

    /**
     * Insert or update a Studio post.
     */
    private function upsert_studio_post($definition)
    {
        $post_name = $this->post_type . '-' . strtolower($definition['identifier']);
        $existing_post = get_page_by_path($post_name, OBJECT, $this->post_type);

        $post_data = [
            'post_title'   => $definition['name'],
            'post_excerpt' => $definition['description'],
            'post_content' => '<!-- wp:' . $this->post_type . '/studio /-->',
            'post_status'  => 'publish',
            'post_type'    => $this->post_type,
        ];

        if ($existing_post) {
            $post_data['ID'] = $existing_post->ID;
            wp_update_post($post_data);
        } else {
            wp_insert_post($post_data);
        }
    }

    /**
     * Generate directory structure for Studio instances.
     */
    private function generate_directory($definition)
    {
        $dir = WP_PLUGIN_DIR . '/' . $this->post_type . '/src/Instances/' . sanitize_key($definition['name']);
        
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $this->generate_block_files($dir, $definition);
    }

    /**
     * Generate block files for studios.
     */
    private function generate_block_files($dir, $definition)
    {
        $template_dir = WP2_EXT_BLOCKSTUDIO_PATH . '/src/Templates/Blocks';

        $templates = [
            'block.json' => $template_dir . '/block.json.tpl',
            'index.php'  => $template_dir . '/index.php.tpl',
        ];

        foreach ($templates as $filename => $template_path) {
            $template = file_get_contents($template_path);
            $template = str_replace('{{name}}', $definition['name'], $template);
            file_put_contents($dir . '/' . $filename, $template);
        }
    }

    /**
     * Generate labels for post types or taxonomies.
     */
    private function generate_labels($singular, $plural)
    {
        return [
            'name'          => __($plural, $this->namespace),
            'singular_name' => __($singular, $this->namespace),
            'add_new_item'  => __('Add New ' . $singular, $this->namespace),
            'edit_item'     => __('Edit ' . $singular, $this->namespace),
            'view_item'     => __('View ' . $singular, $this->namespace),
            'search_items'  => __('Search ' . $plural, $this->namespace),
        ];
    }

    /**
     * Retrieve Studio folders from filesystem.
     */
    private function get_studio_folders()
    {
        $dir = WP_PLUGIN_DIR . '/' . $this->post_type . '/src/Instances';
        return glob($dir . '/*', GLOB_ONLYDIR) ?: [];
    }
}

new Controller();