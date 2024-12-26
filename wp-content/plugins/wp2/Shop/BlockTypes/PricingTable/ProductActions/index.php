<?php
$parent = isset($context['webmultipliers/pricing-table-product']) ? $context['webmultipliers/pricing-table-product'] : null;
$product_id = isset($parent['product']) ? $parent['product'] : null;

if (!$product_id) {
	$product_id = isset($block['postId']) ? $block['postId'] : null;
}

$product = get_post($product_id);

if (!$product) {
	return;
}

$merchandise_id = rwmb_meta('product_merchandise_id', '', $product_id);

$has_secondary = true;

?>

<div useBlockProps class="btn-toolbar" role="toolbar" aria-label="Product Actions">

	<div class="btn-group" role="group" aria-label="Product Purchase Actions">
		<a class="btn btn-primary btn-lg lh-sm">Primary</a>
	</div>

	<?php if ($has_secondary): ?>
		<div class="btn-group" role="group" aria-label="Product Info Actions">
			<a class="btn btn-link btn-lg lh-sm">Secondary</a>
		</div>
	<?php endif; ?>

</div>