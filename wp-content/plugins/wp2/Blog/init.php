<?php

namespace WP2\Blog;

class Controller
{
    public function __construct()
    {
        add_action('init', [$this, 'rename_post_type']);
        add_action('init', [$this, 'rename_category_taxonomy']);
        add_action('init', [$this, 'rename_tag_taxonomy']);
    }

    public function rename_post_type()
    {
        global $wp_post_types;

        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'Articles';
        $labels->singular_name = 'Article';
        $labels->add_new = 'Add New Article';
        $labels->add_new_item = 'Add New Article';
        $labels->edit_item = 'Edit Article';
        $labels->new_item = 'New Article';
        $labels->view_item = 'View Article';
        $labels->search_items = 'Search Articles';
        $labels->not_found = 'No articles found';
        $labels->not_found_in_trash = 'No articles found in Trash';
        $labels->all_items = 'All Articles';
        $labels->menu_name = 'Articles';
        $labels->name_admin_bar = 'Article';
    }

    public function rename_category_taxonomy()
    {
        global $wp_taxonomies;

        $labels = &$wp_taxonomies['category']->labels;
        $labels->name = 'Collections';
        $labels->singular_name = 'Collection';
        $labels->menu_name = 'Collections';
        $labels->all_items = 'All Collections';
        $labels->edit_item = 'Edit Collection';
        $labels->view_item = 'View Collection';
        $labels->update_item = 'Update Collection';
        $labels->add_new_item = 'Add New Collection';
        $labels->new_item_name = 'New Collection Name';
        $labels->parent_item = 'Parent Collection';
        $labels->parent_item_colon = 'Parent Collection:';
        $labels->search_items = 'Search Collections';
        $labels->popular_items = 'Popular Collections';
        $labels->separate_items_with_commas = 'Separate collections with commas';
        $labels->add_or_remove_items = 'Add or remove collections';
        $labels->choose_from_most_used = 'Choose from the most used collections';
        $labels->not_found = 'No collections found.';
        $labels->no_terms = 'No collections';
    }

    public function rename_tag_taxonomy()
    {
        global $wp_taxonomies;

        $labels = &$wp_taxonomies['post_tag']->labels;
        $labels->name = 'Topics';
        $labels->singular_name = 'Topic';
        $labels->menu_name = 'Topics';
        $labels->all_items = 'All Topics';
        $labels->edit_item = 'Edit Topic';
        $labels->view_item = 'View Topic';
        $labels->update_item = 'Update Topic';
        $labels->add_new_item = 'Add New Topic';
        $labels->new_item_name = 'New Topic Name';
        $labels->search_items = 'Search Topics';
        $labels->popular_items = 'Popular Topics';
        $labels->separate_items_with_commas = 'Separate topics with commas';
        $labels->add_or_remove_items = 'Add or remove topics';
        $labels->choose_from_most_used = 'Choose from the most used topics';
        $labels->not_found = 'No topics found.';
        $labels->no_terms = 'No topics';
    }
}

new Controller();
