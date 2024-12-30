<?php
$prefix = isset($attributes['prefix']) ? $attributes['prefix'] : '';
$suffix = isset($attributes['suffix']) ? $attributes['suffix'] : '';
$post_id = isset($block['postId']) ? $block['postId'] : '';
$context_post_id = isset($block['context']['postId']) ? $block['context']['postId'] : '';

if (!empty($context_post_id)) {
	$post_id = $context_post_id;
}

if (empty($post_id) && !$isEditor) {
	return;
}

$time_to_read_text = '';

$time_to_read = '';

$post_type = get_post_type($post_id);

$read_time_post_types = ['post', 'page', 'article'];

$is_read_time_post_type = in_array($post_type, $read_time_post_types);

if ($is_read_time_post_type) {

	$minutes = get_post_meta($post_id, 'post_time_to_read', true);

	if (empty($minutes)) {
		return;
	}

	$seconds = $minutes * 60;

	$time_to_read = '';

	if ($seconds < 60) {
		$time_to_read = $seconds . ' Sec';
	} elseif ($seconds < 3600) {
		$time_to_read = ceil($seconds / 60) . ' Min';
	} else {
		$hours = floor($seconds / 3600);
		$minutes = ceil(($seconds - ($hours * 3600)) / 60);
		$time_to_read = $hours . ' Hr ' . $minutes . ' Min';
	}
}

if ($isEditor) {
	$time_to_read = '2 Min';
}

$time_to_read_text = $time_to_read;

?>
<?php if (!empty($time_to_read_text)) : ?>
	<p useBlockProps>
		<RichText tag="span" attribute="prefix" placeholder="Read Time: " tag="strong" />&nbsp;<span><?php echo esc_html($time_to_read_text); ?></span>
	</p>
<?php endif; ?>