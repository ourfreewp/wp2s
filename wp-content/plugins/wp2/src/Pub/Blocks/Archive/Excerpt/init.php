<?php
$context_edit = isset($_GET['context']) && 'edit' == $_GET['context'];

if ( $context_edit ) {
	$archive_page_excerpt = 'The excerpt from the page matching the same slug as this archive type will be displayed here.';
} else {
	$current_archive      = get_queried_object();
	$current_archive_name = $current_archive->has_archive;
	$archive_page         = get_page_by_path( $current_archive_name );
	if ( $archive_page ) {
		$archive_page_excerpt = $archive_page->post_excerpt;
	}
}

?>

<div class="wp-block-post-excerpt">
	<p class="wp-block-post-excerpt__excerpt"><?php echo esc_html($archive_page_excerpt); ?></p>
</div>