<?php

namespace WP2S\Singles\Archives;

class Controller
{
    private $textdomain = 'wp2s';
    private $type       = 'wp2s_archive';
    private $archive    = 'explore';
    private $slug       = ''; // Empty string to remove slug
    private $singular   = 'Directory';
    private $plural     = 'Directories';
    private $menu       = 'Directories';
    private $icon       = 'dashicons-book-alt';

    public function __construct()
    {
        $this->extend_post_type();
    }

    public function extend_post_type()
    {
        add_filter('register_post_type_args', [$this, 'modify_post_type'], 10, 2);
    }

    public function modify_post_type($args, $post_type)
    {
        if ($this->type === $post_type) {
            $args['public'] = true;
            $args['publicly_queryable'] = true;
            $args['show_ui'] = true;
            $args['show_in_menu'] = true;
            if (!in_array('editor', $args['supports'])) {
                $args['supports'][] = 'editor';
            }
            $args['has_archive'] = $this->archive;
            $args['rewrite'] = [
                'slug' => $this->slug,
                'with_front' => false,
            ];
            $args['menu_icon'] = $this->icon;
            $args['labels'] = [
                'name'               => $this->plural,
                'singular_name'      => $this->singular,
                'menu_name'          => $this->menu,
                'name_admin_bar'     => $this->singular,
                'add_new'            => 'Add New',
                'add_new_item'       => 'Add New ' . $this->singular,
                'new_item'           => 'New ' . $this->singular,
                'edit_item'          => 'Edit ' . $this->singular,
                'view_item'          => 'View ' . $this->singular,
                'all_items'          => 'All ' . $this->plural,
                'search_items'       => 'Search ' . $this->plural,
                'parent_item_colon'  => 'Parent ' . $this->plural . ':',
                'not_found'          => 'No ' . strtolower($this->plural) . ' found.',
                'not_found_in_trash' => 'No ' . strtolower($this->plural) . ' found in Trash.',
            ];
        }
        return $args;
    }

    public function get_archive_page($fallback = 'explore')
    {
        $archive_path = '';

        if (is_archive()) {
            if (is_post_type_archive()) {
                $post_type = get_query_var('post_type');
                $archive_link = get_post_type_archive_link($post_type);
            } elseif (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                $archive_link = get_term_link($term);
            } elseif (is_author()) {
                $author_id = get_query_var('author');
                $archive_link = get_author_posts_url($author_id);
            } elseif (is_date()) {
                $archive_link = get_day_link(
                    get_query_var('year'),
                    get_query_var('monthnum'),
                    get_query_var('day')
                );
            }

            if (!empty($archive_link) && !is_wp_error($archive_link)) {
                $archive_path = trim(parse_url($archive_link, PHP_URL_PATH), '/');
            }
        }

        return $archive_path ?: $fallback;
    }

    public function get_archive_name($fallback = 'Archive')
    {
        $page = $this->get_archive_page_data();
        return $page->post_title ?? $fallback;
    }

    public function get_archive_description($fallback = '')
    {
        $page = $this->get_archive_page_data();
        return $page->post_excerpt ?? $fallback;
    }

    public function get_archive_content($fallback = '')
    {
        $page = $this->get_archive_page_data();
        return $page->post_content ?? $fallback;
    }

    private function get_archive_page_data()
    {
        $archive_path = $this->get_archive_page();

        $pages = get_posts([
            'post_type'   => 'page',
            'name'        => $archive_path,
            'numberposts' => 1,
            'orderby'     => 'name',
            'order'       => 'ASC',
            'post_status' => is_user_logged_in() ? 'any' : 'publish',
        ]);

        return $pages[0] ?? null;
    }
}

// Instantiate the controller and extend post type
$controller = new Controller();