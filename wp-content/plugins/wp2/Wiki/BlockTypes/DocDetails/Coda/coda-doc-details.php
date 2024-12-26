<?php
$attributes = isset($attributes) ? $attributes : null;
$attributes_data = isset($attributes['data']) ? $attributes['data'] : null;

// given the current post id, check if coda-doc and if coda-doc-details is set
// if so, return the coda-doc-details which is html. Consider do_blocks
$current_post_id = get_the_ID();
$current_post = get_post($current_post_id);
$current_type = $current_post->post_type;

if ($current_type === 'coda-doc') {
	$coda_doc_details = get_post_meta($current_post_id, 'coda_doc_details', true);
}
?>

<?php if ( $coda_doc_details) : ?>
	<div class="coda-doc-details">
		<?php echo do_blocks($coda_doc_details); ?>
	</div>
<?php endif; ?>
