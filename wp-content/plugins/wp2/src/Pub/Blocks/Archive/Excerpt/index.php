<?php

/**
 * Block Name: Archive Excerpt
 */

$archive_description = '';

if (is_category() || is_tag() || is_tax()) {

	$archive_description = term_description();
} elseif (is_author()) {

	$author_id = get_queried_object_id();
	$author    = get_user_by('ID', $author_id);
	$archive_description = $author->description;
} elseif (is_post_type_archive()) {
	
	$current_post_type = get_post_type_object(get_post_type());

	if ($current_post_type) {

		$archive_slug = '';

		if ($current_post_type && property_exists($current_post_type, 'archive_slug')) {
			$archive_slug = $current_post_type->archive_slug;
		}

		if (empty($archive_slug)) {
			if ($current_post_type) {
				$archive_slug = $current_post_type->name;
			}
		}

		$archive_page = get_page_by_path($archive_slug);

		if ($archive_page) {
			$archive_description = $archive_page->post_excerpt;
		}
	}
}

$processor = new WP_HTML_Tag_Processor( $archive_description );

if ( $processor->next_tag( 'p' ) ) {
    $processor->add_class( 'm-0' );
}

$archive_description = $processor->get_updated_html();

if (!$isEditor && empty($archive_description)) {
	return;
}

if ($isEditor) {
	$archive_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit aliquam purus ac libero ultricies aliquam.';
}

?>

<div useBlockProps>
	<?php echo wp_kses($archive_description, 'post'); ?>
</div>