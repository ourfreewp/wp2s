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

$product_name = $product->post_title;

if ( ! $product_name ) {
	return;
}

?>

<p useBlockProps class="card-title h2 fw-semibold">
	<?php echo esc_html( $product_name ); ?>
</p>