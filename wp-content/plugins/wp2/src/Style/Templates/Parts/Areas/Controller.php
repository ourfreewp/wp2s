<?php

namespace WP2\Style\Templates\Parts\Areas;

/**
 * Controller to manage template part areas.
 */
class Controller
{
    public function __construct()
    {
        add_filter('default_wp_template_part_areas', [$this, 'register_template_part_areas']);
    }

    /**
     * Register custom template part areas.
     *
     * @param array $areas Existing template part areas.
     * @return array Modified template part areas.
     */
    public function register_template_part_areas($areas)
    {
        return array_merge($areas, $this->get_custom_areas());
    }

    /**
     * Define custom template part areas.
     *
     * @return array Custom areas to add to the template parts.
     */
    private function get_custom_areas()
    {
        $text_domain = 'onthewater';

        return [
            [
                'area'        => 'main-content',
                'area_tag'    => 'section',
                'label'       => __('Main Contents', $text_domain),
                'description' => '',
                'icon'        => 'layout',
            ],
            [
                'area'        => 'site-header',
                'area_tag'    => 'header',
                'label'       => __('Site Header', $text_domain),
                'description' => __('Designed to be placed directly in a template in site editor. One per template.', $text_domain),
                'icon'        => 'header',
            ],
            [
                'area'        => 'site-footer',
                'area_tag'    => 'footer',
                'label'       => __('Site Footer', $text_domain),
                'description' => __('Designed to be placed directly in a template in site editor. One per template.', $text_domain),
                'icon'        => 'footer',
            ],
            [
                'area'        => 'main',
                'area_tag'    => 'main',
                'label'       => __('Main Content', $text_domain),
                'description' => __('Designed to be placed directly in a template in site editor.', $text_domain),
                'icon'        => 'layout',
            ],
            [
                'area'        => 'main-header',
                'area_tag'    => 'header',
                'label'       => __('Main Header', $text_domain),
                'description' => __('Designed to be placed in a main template area. One per template.', $text_domain),
                'icon'        => 'layout',
            ],
            [
                'area'        => 'main-query',
                'area_tag'    => 'section',
                'label'       => __('Main Query', $text_domain),
                'description' => __('Designed to be placed in a main template area.', $text_domain),
                'icon'        => 'layout',
            ],
            [
                'area'        => 'main-article',
                'area_tag'    => 'article',
                'label'       => __('Main Article', $text_domain),
                'description' => __('Designed to be placed in a single template area. One per template.', $text_domain),
                'icon'        => 'layout',
            ],
            [
                'area'        => 'navbar',
                'area_tag'    => 'div',
                'label'       => __('Navbar', $text_domain),
                'description' => __('Designed to be placed directly in a template. One per template.', $text_domain),
                'icon'        => 'layout',
            ],
        ];
    }
}