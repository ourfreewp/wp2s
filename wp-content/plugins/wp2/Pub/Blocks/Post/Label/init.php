<?php


function build_metabox_post_display_settings() {

	$prefix = '_otw_';

	// -------------------------------
	// Initiate the metabox.
	// -------------------------------
	$cmb = new_cmb2_box( array(
		'id'            => 'otw2_post_display_settings',
		'title'         => __( 'Post Display Settings', 'otw2' ),
		'object_types'  => array( 'post', 'news', 'forecasts', 'video' ),
		'context'       => 'after_editor',
		'priority'      => 'high',
		'show_names'    => true,
	) );

	// -------------------------------
	// Add the fields to metabox.
	// -------------------------------
	$cmb->add_field( array(
		'name'             => 'Post Label',
		'desc'             => 'Optionally choose category, blog, or general subject to use as label for post where needed.',
		'id'               => $prefix . 'selected_post_label',
		'type'             => 'select',
		'show_option_none' => true,
		'default'          => '',
		'options_cb'       => __NAMESPACE__ . '\display_label_options',
	) );

}


function display_label_options( $field ) {
	$cat_ops = [];
	$cats = get_terms( 'category', 'parent=0&hide_empty=1' );
	foreach ( $cats as $cat ) {
		$cat_ops[ $cat->slug ] = $cat->name;
	}
	$cpt_ops = [
		'news'      => 'News',
		'forecasts' => 'Report',
		'video'     => 'Video',
	];
	$options = array_merge( $cat_ops, $cpt_ops );
	return $options;
}

function set_post_label() {

	$selected_label = get_post_meta( get_the_ID(), '_otw_selected_post_label', true );
	if ( '' === $selected_label ) {
		return;
	}
	if ( post_type_exists( $selected_label ) ) {
		switch ( $selected_label ) {
			case 'news':
				$type_name = 'News';
				break;
			case 'video':
				$type_name = 'Videos';
				break;
			case 'forecasts':
				$type_name = 'Forecasts';
				break;
			default:
				$type_name = 'Article';
				break;
		}
		$post_label = [
			'slug' => $selected_label,
			'name' => $type_name,
			'url'  => get_post_type_archive_link( $selected_label ),
		];
	} else {
		$term = get_term_by( 'slug', $selected_label, 'category' );
		$post_label = [
			'slug' => $term->slug,
			'name' => $term->name,
			'url'  => get_term_link( $term->slug, 'category' ),
		];
	}
	update_post_meta( get_the_ID(), '_otw_post_label', $post_label );
}

/**
 * Display the categories a post is classified under.
 *
 * @param int    $post_id — the post ID.
 * @param string $label_class — optional class to add to the link.
 */
function otw2_post_label( $post_id, $label_class = '' ) {

	// Check for current post's post label.
	if ( isset( get_post_meta( $post_id, '_otw_post_label', false )[0] ) ) {
		$current_label = get_post_meta( $post_id, '_otw_post_label', false )[0];
	} else {
		$current_label = false;
	}
	// If this current post meta is a valid post label, lets use it.
	if ( $current_label && ! is_wp_error( $current_label['url'] ) ) {

		$post_label = $current_label;
	// If we're missing a valid saved post label meta field, let's build one.
	} else {

		$cats = get_the_terms( $post_id, 'category' );
		// Check if the current post has categories attached to it.
		if ( $cats ) {

			$top_cats = [];

			foreach ( $cats as $cat ) {
				if ( 0 === $cat->parent && 'uncategorized' !== $cat->slug ) {
					$top_cats[] = $cat;
				}
			}
		}
		// If the post has top level categories, we'll use those to set default label.
		if ( ! empty( $top_cats ) ) {

			$set_cat = $top_cats[0];

			$cat_url = get_term_link( $set_cat->slug, 'category' );
			if ( is_wp_error( $cat_url ) ) {
				$cat_url = '#';
			}

			$post_label = [
				'slug' => $set_cat->slug,
				'name' => $set_cat->name,
				'url'  => esc_url( $cat_url ),
			];

		} else {
			// If the post doesn't have top level categories, we'll use the post type.
			$post_type = get_post_type_object( get_post_type( $post_id ) );
			$post_label = [
				'slug' => $post_type->name,
				'name' => $post_type->labels->singular_name,
				'url'  => get_post_type_archive_link( $post_type->name ),
			];

		}

		update_post_meta( $post_id, '_otw_post_label', $post_label );

	}
	?>
	<a href="<?php echo esc_url( $post_label['url'] ); ?>" class="PostLabel <?php echo esc_attr( $label_class ); ?>"><?php echo esc_html( $post_label['name'] ); ?></a>
	<?php
}