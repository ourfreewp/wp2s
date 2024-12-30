<?php

namespace WP2\Studio\Helpers\Types\Post;

use WP2\Studio\Helpers\Definitions\Controller as DefinitionsController;

class Controller
{
    private string $async_action = 'wp2_studio_type_post_scan';
    private array $definitions = [];

    public function __construct()
    {
        add_action('init', [$this, 'register_post_types']);
    }

    /**
     * Register Post Types using definitions from the Definitions Controller.
     *
     * @return void
     */
    public function register_post_types(): void
    {
        $definitions = $this->fetch_definitions();

        foreach ($definitions as $definition) {
            $this->process_definition($definition);
        }
    }

    /**
     * Fetch Definitions from Definitions Controller.
     *
     * @return array The array of definitions.
     */
    private function fetch_definitions(): array
    {
        $controller = new DefinitionsController();
        $this->definitions = $controller->get_studio_definitions();

        return $this->definitions;
    }

    /**
     * Process each definition and register post types.
     *
     * @param array $definition The definition to process.
     * @return void
     */
    private function process_definition(array $definition): void
    {
        if (empty($definition['post_types']) || !is_array($definition['post_types'])) {
            return;
        }

        $prefix = 'wp2-studio';

        foreach ($definition['post_types'] as $post_type_data) {
            $this->register_custom_post_type($prefix, $post_type_data);
        }
    }

    /**
     * Register a single post type from the definition.
     *
     * @param string $prefix Prefix for the post type.
     * @param array  $post_type_data Data for the post type.
     * @return void
     */
    private function register_custom_post_type(string $prefix, array $post_type_data): void
    {
        if (empty($post_type_data['post_type']) || empty($post_type_data['args'])) {
            return;
        }

        $post_type = $prefix . '_' . sanitize_key($post_type_data['post_type']);
        $args = (array) $post_type_data['args'];

        register_post_type($post_type, $args);
    }
}