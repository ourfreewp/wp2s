<?php
$post_id         = isset($block['postId']) ? $block['postId'] : '';
$context_post_id = isset($block['context']['postId']) ? $block['context']['postId'] : '';

if (!empty($context_post_id)) {
	$post_id = $context_post_id;
}

$published_formatted = get_the_date('F j, Y', $post_id);
$published_datetime  = get_the_date('c', $post_id);

$modified_formatted  = get_the_modified_date('F j, Y', $post_id);
$modified_datetime   = get_the_modified_date('c', $post_id);

$show_modified = true;

if (strtotime($modified_datetime) - strtotime($published_datetime) < 300) {
	$show_modified = false;
}

if ($isEditor) {
	$show_modified = true;
	
	$published_formatted = 'October 21, 2018';
	$published_datetime  = '2018-10-21T00:00:00';

	$modified_formatted  = 'October 22, 2018';
	$modified_datetime   = '2018-10-22T00:00:00';
}

?>

<div useBlockProps>
	<p class="wp-block-post-date">
		<RichText attribute="prefixPublished" tag="span" placeholder="Published" /> <time datetime="<?php echo esc_html($published_datetime); ?>"><?php echo esc_html($published_formatted); ?></time>
	</p>
	<p class="wp-block-post-date <?php echo $show_modified ? 'wp-block-post-date__modified-date' : 'wp-block-post-date__modified-date visually-hidden'; ?>">
		<RichText attribute="prefixModified" tag="span" placeholder="Updated" /> <time datetime="<?php echo esc_html($modified_datetime); ?>"><?php echo esc_html($modified_formatted); ?></time>
	</p>
</div>