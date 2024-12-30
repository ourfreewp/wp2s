<?php
$parent = isset($context['webmultipliers/pricing-table']) ? $context['webmultipliers/pricing-table'] : null;

$layout = isset($parent['layout']) ? $parent['layout'] : null;

$layout_value = isset($layout['value']) ? $layout['value'] : 1;

$layout_value = intval($layout_value);

$template = [
	['webmultipliers/pricing-table-product'],
];

?>

<InnerBlocks renderAppender="button" template="<?php echo esc_attr(wp_json_encode($template)); ?>" useBlockProps />
