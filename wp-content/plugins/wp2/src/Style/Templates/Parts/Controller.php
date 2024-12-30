<?php

namespace WP2\Style\Templates\Parts;

/**
 * Controller to manage template parts for WP2 Style.
 */
class Controller
{
    public function __construct()
    {
        add_filter('wp_theme_json_data_theme', [$this, 'register_template_parts']);
    }

    /**
     * Register custom template parts.
     *
     * @param object $theme_json The theme JSON data object.
     * @return object Updated theme JSON data.
     */
    public function register_template_parts($theme_json)
    {
        $template_parts = $this->get_template_parts();

        $new_data = [
            "version" => 3,
            "templateParts" => $template_parts,
        ];

        return $theme_json->update_with($new_data);
    }

    /**
     * Define custom template parts.
     *
     * @return array List of template parts.
     */
    private function get_template_parts()
    {
        return [
            ['title' => 'Main: 404', 'name' => 'main-part-404', 'area' => 'main'],
            ['title' => 'Main: Archive', 'name' => 'main-part-archive', 'area' => 'main'],
            ['title' => 'Main: Search Archive', 'name' => 'main-part-archive-search', 'area' => 'main'],
            ['title' => 'Main: Video Archive', 'name' => 'main-part-archive-video', 'area' => 'main'],
            ['title' => 'Main: Author', 'name' => 'main-part-author', 'area' => 'main'],
            ['title' => 'Main: Region Archive', 'name' => 'main-part-archive-region', 'area' => 'main'],
            ['title' => 'Main: Front Page', 'name' => 'main-part-front-page', 'area' => 'main'],
            ['title' => 'Main: Magazine Page', 'name' => 'main-part-page-magazine', 'area' => 'main'],
            ['title' => 'Main: Index', 'name' => 'main-part-index', 'area' => 'main'],
            ['title' => 'Main: Page', 'name' => 'main-part-page', 'area' => 'main'],
            ['title' => 'Main: Search Page', 'name' => 'main-part-search', 'area' => 'main'],
            ['title' => 'Main: Single', 'name' => 'main-part-single', 'area' => 'main'],

            ['title' => 'Main Header: Archive', 'name' => 'main-header-part-archive', 'area' => 'main-header'],
            ['title' => 'Main Header: Author', 'name' => 'main-header-part-author', 'area' => 'main-header'],
            ['title' => 'Main Header: Search', 'name' => 'main-header-part-search', 'area' => 'main-header'],

            ['title' => 'Main Article: Single', 'name' => 'main-article-part-single', 'area' => 'main-article'],
            ['title' => 'Main Article: Page', 'name' => 'main-article-part-page', 'area' => 'main-article'],

            ['title' => 'Main Query: Default', 'name' => 'main-query-part', 'area' => 'main-query'],
            ['title' => 'Main Query: Search', 'name' => 'main-query-part-search', 'area' => 'main-query'],
            ['title' => 'Main Query: Author', 'name' => 'main-query-part-author', 'area' => 'main-query'],

            ['title' => 'Site Footer', 'name' => 'site-footer', 'area' => 'site-footer'],
            ['title' => 'Site Header', 'name' => 'site-header', 'area' => 'site-header'],

            ['title' => 'Site Menu', 'name' => 'site-navbar-menu-collapsed', 'area' => 'navbar'],
            ['title' => 'Site Search', 'name' => 'site-navbar-search-collapsed', 'area' => 'navbar'],
            ['title' => 'Site Regions', 'name' => 'site-navbar-regions', 'area' => 'navbar'],
            ['title' => 'Primary Nav', 'name' => 'site-navbar-primary', 'area' => 'navbar'],
            ['title' => 'Secondary Nav', 'name' => 'site-navbar-secondary', 'area' => 'navbar'],
        ];
    }
}