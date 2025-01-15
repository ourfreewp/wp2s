<?php
// Path: wp-content/plugins/wp2s/Blocks/InnerBlocksMask/index.php
namespace WP2S\Blocks\InnerBlocksMask;

$controller = new Controller();
$mask = $a['mask'] ?? '1';

// Generate rotated SVGs for each corner if mask is 2
$svg_0   = $mask == '2' ? $controller->get_svg(0) : '';
$svg_90  = $mask == '2' ? $controller->get_svg(90) : '';
$svg_180 = $mask == '2' ? $controller->get_svg(180) : '';
$svg_270 = $mask == '2' ? $controller->get_svg(270) : '';
?>

<div <?php echo get_block_wrapper_attributes(['class' => "wp2s-masked-blocks wp2s-mask-" . esc_attr($mask)]); ?>>
    <?php if ($mask == '2') : ?>
        <div class="wp2s-mask-overlay top-left"><?php echo $svg_0; ?></div>
        <div class="wp2s-mask-overlay top-right"><?php echo $svg_90; ?></div>
        <div class="wp2s-mask-overlay bottom-left"><?php echo $svg_270; ?></div>
        <div class="wp2s-mask-overlay bottom-right"><?php echo $svg_180; ?></div>
    <?php endif; ?>
    <InnerBlocks />
</div>