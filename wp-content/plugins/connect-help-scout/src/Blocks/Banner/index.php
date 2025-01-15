<?php
namespace WP2\Connect\Klaviyo\Blocks\Banner;

$form_id  = $a['form_id'] ?? '';
$block_id = $b['id'] ?? '';
$block_id = preg_replace('/[^a-zA-Z0-9_]/', '_', $block_id);
$position = $a['position'] ?? 'bottom';
$height   = $a['height'] ?? '60px';
$unique_id = uniqid();
$handle   = 'wp2-klaviyo-form-' . $block_id . '-' . $unique_id;
$object_name = 'wp2_klaviyo_' . $block_id . '_' . $unique_id;

$localized_data = [
    'form_id' => $form_id,
    'block_id' => $block_id,
    'type' => 'banner',
    'position' => $position,
    'height' => $height,
];

wp_register_script($handle, '', [], null, true);
wp_enqueue_script($handle);
wp_localize_script($handle, $object_name, $localized_data);
?>

<style>
    :root {
        --wp2-klaviyo-banner-padding-<?php echo esc_html($position); ?>: <?php echo esc_html($height); ?>
    }
</style>

<script type="text/javascript">
    window._klOnsite = window._klOnsite || [];
    <?php if (!empty($form_id)) : ?>
        window._klOnsite.push(['openForm', '<?php echo esc_js($form_id); ?>']);
    <?php else : ?>
        console.warn('Klaviyo form ID missing for block: <?php echo esc_js($block_id); ?>');
    <?php endif; ?>
</script>