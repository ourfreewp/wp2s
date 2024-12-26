<?php
$template = [
	[
		"core/post-content"
	],
	[
		"onthewater/post-content-empty"
	]
];
$allowed_blocks = $template;
?>
<InnerBlocks 
	useBlockProps 
	tag="div" 
	allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks));?>"  
	template="<?php echo esc_attr(wp_json_encode($template));?>" 
	templateLock="all"
/>