<?php

namespace WP2\Style\Templates\Types;

/**
 * Controller to manage default template types.
 */
class Controller
{
    public function __construct()
    {
        add_filter('default_template_types', [$this, 'set_default_templates']);
    }

    /**
     * Define and modify the default template types.
     *
     * @param array $templates Existing template types.
     * @return array Modified template types.
     */
    public function set_default_templates($templates)
    {
        return array_merge($templates, $this->get_custom_templates());
    }

    /**
     * Custom template definitions.
     *
     * @return array Custom templates with titles and descriptions.
     */
    private function get_custom_templates()
    {
        return [
            'archive' => [
                'title'       => 'Archive: Fallback',
                'description' => 'The fallback template type for archive views.',
            ],
            'index' => [
                'title'       => 'Fallback',
                'description' => 'The fallback template type for the site.',
            ],
            'single' => [
                'title'       => 'Single Item: Fallback',
                'description' => 'The fallback template type for single views.',
            ],
            '404' => [
                'title'       => 'Page: 404',
                'description' => 'The template type for the 404 page.',
            ],
            'home' => [
                'title'       => 'Page: Blog Page',
                'description' => 'The template type for the blog entries archive page.',
            ],
            'front-page' => [
                'title'       => 'Page: Front Page',
                'description' => 'The template type for the front page of the site.',
            ],
            'search' => [
                'title'       => 'Page: Search Results',
                'description' => 'The template type for the search results page.',
            ],
            'author' => [
                'title'       => 'Single Item: Author',
                'description' => 'The template type for the author archive page.',
            ],
            'single-article' => [
                'title'       => 'Single Item: Article',
                'description' => 'The template type for the article post.',
            ],
            'single-slideshow' => [
                'title'       => 'Single Item: Slideshow',
                'description' => 'The template type for the slideshow post.',
            ],
            'page' => [
                'title'       => 'Single Item: Page',
                'description' => 'The fallback template type for page views.',
            ],
            'single-post' => [
                'title'       => 'Single Item: Blog Post',
                'description' => 'The template type for the post view.',
            ],
            'tag' => [
                'title'       => 'Single Item: Tag',
                'description' => 'The template type for the tag archive page.',
            ],
        ];
    }
}