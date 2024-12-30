<?php if (strip_tags($innerBlocks) === '') {
  return;
} ?>
<?php
$template = [
	[
		"core/group"
	],
];
?>
<InnerBlocks 
	tag="div" 
	template="<?php echo esc_attr(wp_json_encode($template));?>" 
/>