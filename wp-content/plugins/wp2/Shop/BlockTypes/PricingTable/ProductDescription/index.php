<?php
$parent         = $context['webmultipliers/pricing-table-product'];
$product_id	    = isset($parent['product']['value']) ? $parent['product']['value'] : null;

if ( ! $product_id ) {
	return;
}

$product = get_post( $product_id );

if ( ! $product ) {
	return;
}

$product_excerpt = $product->post_excerpt;

if ( ! $product_excerpt ) {
	return;
}

?>

<div useBlockProps class="card-text mt-1 fs-5">
	<?php echo wp_kses($product_excerpt, 'post'); ?>
</div>

