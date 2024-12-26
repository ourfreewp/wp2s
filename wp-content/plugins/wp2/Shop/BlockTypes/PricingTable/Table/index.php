<?php
$template = [
	[
		'core/cover',
		[],
		[
			['core/heading', ['content' => 'Pricing Table']],
			['core/paragraph', ['content' => 'Select an option.']],
		]
	]
];

$layout = isset($attributes['layout']) ? $attributes['layout'] : 1;

$layout = intval($layout);

$layouts = [
	1 => 'row',
	2 => 'row row-cols-2',
	3 => 'row row-cols-3',
	4 => 'row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4',
];

$classes = isset($layouts[$layout]) ? $layouts[$layout] : 'row';

?>
<div class="container px-lg-0" useBlockProps>
	<InnerBlocks renderAppender="button" template="<?php echo esc_attr(wp_json_encode($template)); ?>" useBlockProps  class="<?php echo esc_attr($classes); ?>" />
</div>