<?php

$share_actions = [
	[
		'novashare/share',
		[
			'lock' => [
				'remove' => true,
				'move' => true,
			]
		],
		[
			[
				'novashare/share-network',
				[
					"network" => "share"
				],
			]
		]
	]
];

$share_actions_template = wp_json_encode($share_actions);

?>

<InnerBlocks useBlockProps template="<?php echo esc_attr($share_actions_template); ?>" templateLock="all"/>
