<?php
$parent = $context['webmultipliers/pricing-table-product'];

if (!$parent) {
	return;
}

$product_id	= isset($parent['product']['value']) ? $parent['product']['value'] : null;

if (!$product_id) {
	return;
}

$product = get_post($product_id);

if (!$product) {
	return;
}

$thumbnail = get_the_post_thumbnail($product, [150,150], ['class' => 'rounded-circle']);

if (!$thumbnail) {
	return;
}

?>

<div useBlockProps class="card-img-top px-3 pt-5 pb-4">
	<?php echo wp_kses($thumbnail, 'post'); ?>
</div>

