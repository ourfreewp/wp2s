<?php

// Path: wp-content/plugins/wp2s/src/Blocks/Brand/index.php
namespace WPS2\Blocks\Brand;

$controller = new Controller();

$identity = $a['identity'] ?? 'Site';
$kind = $a['kind'] ?? 'Logo';
$theme = $a['theme'] ?? 'light';
$type = $a['type'] ?? 'svg';

$class = "wp2s-brand--" . strtolower($identity) . "-" . strtolower($kind);

$asset = $controller->get_brand_asset($identity, $kind, $theme, $type);
?>
<?php if (!empty($asset)) : ?>
    <div useBlockProps class="wp2s-brand <?php echo esc_attr($class); ?>">
        <?php echo $asset; ?>
    </div>
<?php else : ?>
    <div class="wp2s-brand-placeholder">
        <span class="wp2s-brand-placeholder__text">Brand asset not found.</span>
    </div>
<?php endif; ?>