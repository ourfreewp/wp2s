<?php
$tag = isset($attributes['tag']) ? $attributes['tag'] : 'section';
$template = [
	[
		'core/group',
		[],
		[
			[
				'custom/query-item',
				[],
				[]
			],
			[
				'custom/query-item',
				[],
				[]
			],
			[
				'custom/query-item',
				[],
				[]
			],
		]
	],
];
?>
<InnerBlocks useBlockProps tag="<?php echo esc_attr($tag); ?>" template="<?php echo esc_attr(wp_json_encode($template)); ?>" />