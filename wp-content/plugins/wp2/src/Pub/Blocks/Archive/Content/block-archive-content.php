<?php
$context_edit = isset($_GET['context']) && 'edit' == $_GET['context'];

if ( $context_edit ) {
	$archive_page_content = '';
	$achive_page_content = do_blocks('
		<!-- wp:paragraph -->
		<p>The content of the archive from the page that matches the slug.</p>
		<!-- /wp:paragraph -->'
	);
} else {
	$current_archive      = get_queried_object();
	$current_archive_name = $current_archive->has_archive;
	$archive_page         = get_page_by_path( $current_archive_name );
	if ( $archive_page ) {
		$archive_page_content = $archive_page->post_content;
		$archive_page_content = do_blocks($archive_page_content);
	}
}

?>

<div class="entry-content wp-block-post-content">
	<?php echo $archive_page_content; ?>
</div>