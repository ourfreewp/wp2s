<?php
$settings   = ONTHEWATER_SETTINGS_OPTION_BRANDING;
$mobile     = rwmb_meta('brand_image_mobile', ['object_type' => 'setting'], $settings);
$desktop    = rwmb_meta('brand_image_desktop', ['object_type' => 'setting'], $settings);
$variations = [
	[
		'name' => 'mobile',
		'attachment_id' => array_key_first($mobile),
	],
	[
		'name' => 'desktop',
		'attachment_id' => array_key_first($desktop),
	],
];
$stretched_link = onthewater_get_stretched_link(get_home_url(), get_bloginfo('name'), __('Go to the homepage of', 'onthewater'), get_bloginfo('name'));
?>

<div useBlockProps>

	<?php foreach ($variations as $variation) : ?>
		<figure class="wp-block-onthewater-brand-image is-<?php echo $variation['name']; ?>">
			<?php echo wp_get_attachment_image($variation['attachment_id'], 'full'); ?>
		</figure>
	<?php endforeach; ?>

	<?php echo ($isEditor ? '' : $stretched_link); ?>
</div>