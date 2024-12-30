<?php
$parent         = $context['webmultipliers/pricing-table-product'];

if (!$parent) {
	return;
}

$product_id = isset($parent['product']['value']) ? $parent['product']['value'] : null;

if (!$product_id) {
	return;
}

$product = get_post($product_id);

if (!$product) {
	return;
}
$price       = get_post_meta($product->ID, 'product_price', true);
$plan_option = get_post_meta($product->ID, 'product_plan_option', true);

if (!$price && !$plan_option) {
	return;
}

?>

<div useBlockProps class="d-flex justify-content-start">
	<?php if (!empty($price)) : ?>
		<div class="h2 fw-semibold mb-0">
			<?php echo esc_html($price); ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($plan_option)) : ?>
		<div class="h5 ms-2 align-self-end mb-1">
			<?php echo esc_html($plan_option); ?>
		</div>
	<?php endif; ?>
</div>