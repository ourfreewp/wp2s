<?php

$block = isset($block) ? $block : null;
$id = isset($block['id']) ? $block['id'] : null;
$name = isset($block['name']) ? $block['name'] : null;
$postId = isset($block['postId']) ? $block['postId'] : null;
$postType = isset($block['postType']) ? $block['postType'] : null;

$index = isset($block['index']) ? $block['index'] : null;
$indexTotal = isset($block['indexTotal']) ? $block['indexTotal'] : null;

$context = isset($block['context']) ? $block['context'] : null;
$context_postId = isset($context['postId']) ? $context['postId'] : null;
$context_postType = isset($context['postType']) ? $context['postType'] : null;

$attributes = isset($attributes) ? $attributes : null;
$product = isset($attributes['product']) ? $attributes['product'] : null;
$layout = isset($attributes['layout']) ? $attributes['layout'] : null;

$classes = [
	'product-layout-' . $layout,
];

$classes = implode(' ', $classes);

$template = [
	[
		'webmultipliers/pricing-table-product-badge'
	],
	[
		'webmultipliers/pricing-table-product-image'
	],
	[
		'core/group',
		[
			'className' => 'card-body'
		],
		[
			[
				'webmultipliers/pricing-table-product-name'
			],
			[
				'webmultipliers/pricing-table-product-description'
			],
			[
				'webmultipliers/pricing-table-product-pricing'
			],
			[
				'webmultipliers/pricing-table-product-actions'
			]
		]
	],
	[
		'webmultipliers/pricing-table-product-features'
	],
];

?>

<InnerBlocks renderAppender="button" template="<?php echo esc_attr(wp_json_encode($template)); ?>" useBlockProps
	class="card shadow-sm <?php echo esc_attr($classes); ?>" />