<?php
/**
 * 
 * Plugin Name: VSG FAQs
 * Description: A simple plugin to create and display FAQs
 */

$faqs = new VSG_FAQs();

function vsg_faqs_init() {
	global $faqs;

	$faqs->faq_post_type();

	add_filter('rwmb_meta_boxes', [$faqs, 'register_blocks'], 9999);

	add_filter('rwmb_meta_boxes', [$faqs, 'register_fields'], 9999);

	$faqs->register_taxonomies();
	$faqs->post_fields();
	
}

add_action('init', 'vsg_faqs_init');

class VSG_FAQs 
{



	public function faq_post_type() {
		$text_domain = sanitize_title(get_bloginfo('name'));
		$labels = [
			'name'                     => esc_html__( 'FAQs', 'your-textdomain' ),
			'singular_name'            => esc_html__( 'FAQ', 'your-textdomain' ),
			'add_new'                  => esc_html__( 'Add New', 'your-textdomain' ),
			'add_new_item'             => esc_html__( 'Add New FAQ', 'your-textdomain' ),
			'edit_item'                => esc_html__( 'Edit FAQ', 'your-textdomain' ),
			'new_item'                 => esc_html__( 'New FAQ', 'your-textdomain' ),
			'view_item'                => esc_html__( 'View FAQ', 'your-textdomain' ),
			'view_items'               => esc_html__( 'View FAQs', 'your-textdomain' ),
			'search_items'             => esc_html__( 'Search FAQs', 'your-textdomain' ),
			'not_found'                => esc_html__( 'No faqs found.', 'your-textdomain' ),
			'not_found_in_trash'       => esc_html__( 'No faqs found in Trash.', 'your-textdomain' ),
			'parent_item_colon'        => esc_html__( 'Parent FAQ:', 'your-textdomain' ),
			'all_items'                => esc_html__( 'All FAQs', 'your-textdomain' ),
			'archives'                 => esc_html__( 'FAQ Archives', 'your-textdomain' ),
			'attributes'               => esc_html__( 'FAQ Attributes', 'your-textdomain' ),
			'insert_into_item'         => esc_html__( 'Insert into faq', 'your-textdomain' ),
			'uploaded_to_this_item'    => esc_html__( 'Uploaded to this faq', 'your-textdomain' ),
			'featured_image'           => esc_html__( 'Featured image', 'your-textdomain' ),
			'set_featured_image'       => esc_html__( 'Set featured image', 'your-textdomain' ),
			'remove_featured_image'    => esc_html__( 'Remove featured image', 'your-textdomain' ),
			'use_featured_image'       => esc_html__( 'Use as featured image', 'your-textdomain' ),
			'menu_name'                => esc_html__( 'FAQs', 'your-textdomain' ),
			'filter_items_list'        => esc_html__( 'Filter faqs list', 'your-textdomain' ),
			'filter_by_date'           => esc_html__( '', 'your-textdomain' ),
			'items_list_navigation'    => esc_html__( 'FAQs list navigation', 'your-textdomain' ),
			'items_list'               => esc_html__( 'FAQs list', 'your-textdomain' ),
			'item_published'           => esc_html__( 'FAQ published.', 'your-textdomain' ),
			'item_published_privately' => esc_html__( 'FAQ published privately.', 'your-textdomain' ),
			'item_reverted_to_draft'   => esc_html__( 'FAQ reverted to draft.', 'your-textdomain' ),
			'item_scheduled'           => esc_html__( 'FAQ scheduled.', 'your-textdomain' ),
			'item_updated'             => esc_html__( 'FAQ updated.', 'your-textdomain' ),
			'text_domain'              => esc_html__( 'your-textdomain', 'your-textdomain' ),
		];
		$args = [
			'label'               => esc_html__( 'FAQs', $text_domain ),
			'labels'              => $labels,
			'description'         => '',
			'public'              => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'query_var'           => true,
			'can_export'          => true,
			'delete_with_user'    => true,
			'has_archive'         => false,
			'rest_base'           => '',
			'show_in_menu'        => true,
			'menu_position'       => '',
			'menu_icon'           => 'dashicons-admin-generic',
			'capability_type'     => 'post',
			'supports'            => ['title', 'excerpt', 'custom-fields', 'revisions'],
			'taxonomies'          => [],
			'rewrite'             => [
				'with_front' => false,
			],
		];

		register_post_type( 'faq', $args );
	}

	public function register_taxonomies() {
		
		register_taxonomy('faq_collection', 'faq', [
			'label' => 'Collections',
			'public' => true,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'show_tagcloud' => true,
			'show_in_quick_edit' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
			'rewrite' => [
				'slug' => 'faq-collections',
				'with_front' => false,
				'hierarchical' => true,
			],
		]);
	}

	public function register_blocks($meta_boxes)
	{

		$meta_boxes[] = [
			'title'           => 'Featured FAQs',
			'id'              => 'featured-faqs',
			'description'     => '',
			'type'            => 'block',
			'icon'            => 'admin-page',
			'render_callback' => function ($attributes, $is_preview, $post) {
				include plugin_dir_path(__FILE__) . 'blocks/featured-faqs.php';
			},
			'enqueue_style'   => plugin_dir_url(__FILE__) . 'assets/css/featured-faqs.css',
			'supports' => [
				'align' => ['wide', 'full'],
			]
		];

		$meta_boxes[] = [
			'title'           => 'FAQs by Collection',
			'id'              => 'faqs-by-collection',
			'description'     => '',
			'type'            => 'block',
			'icon'            => 'admin-page',
			'render_callback' => function ($attributes, $is_preview, $post) {
				include plugin_dir_path(__FILE__) . 'blocks/faqs-by-collection.php';
			},
			'enqueue_assets' => function () {
				wp_enqueue_style('featured-faqs', plugin_dir_url(__FILE__) . 'assets/css/featured-faqs.css');
				wp_enqueue_style('faqs-by-collection', plugin_dir_url(__FILE__) . 'assets/css/faqs-by-collection.css');
			},
			'supports' => [
				'align'    => ['wide', 'full'],
				'multiple' => false,
			]
		];

		return $meta_boxes;
	}

	public function register_fields($meta_boxes)
	{

		$meta_boxes[] = [
			'title'  => 'FAQ Details',
			'id'     => 'faq-details',
			'type'   => 'post',
			'context' => 'side',
			'priority' => 'default',
			'autosave' => true,
			'post_types' => ['faq'],
			'fields' => [
				[
					'id'   => 'sticky',
					'name' => 'Sticky',
					'type' => 'checkbox',
				],
				[
					'id' => 'position',
					'name' => 'Position',
					'type' => 'number',
				]
			],
		];

		return $meta_boxes;
	}


	public function post_fields()
	{

		register_post_meta('faq', 'sticky', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'boolean',
		]);

		register_post_meta('faq', 'position', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'number',
		]);

	}

}