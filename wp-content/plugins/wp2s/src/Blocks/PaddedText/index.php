<?php
// Path: wp-content/plugins/wp2s/Blocks/PaddedText/index.php

namespace WP2S\Blocks\PaddedText;

$tag = $a['tag'] ?? 'h1'; // Default tag is h1
$text = $a['text'] ?? 'Hello, World!'; // Default text content

// Construct dynamic classes
$classes = ['wp2s-padded-text'];
if (!empty($b['backgroundColor'])) {
    $classes[] = 'has-' . $b['backgroundColor'] . '-background-color';
    $classes[] = 'has-background';
}
if (!empty($b['textColor'])) {
    $classes[] = 'has-' . $b['textColor'] . '-color';
    $classes[] = 'has-text-color';
}
$classString = implode(' ', $classes);

?>

<div class="wp2s-padded-text <?php echo esc_attr($classString); ?>">
    <<?php echo esc_attr($tag); ?> useBlockProps>
        <?php echo esc_html($text); ?>
    </<?php echo esc_attr($tag); ?>>
</div>

<style>
    :root {
        --wp2s-padded-text-padding-left: <?php echo esc_attr($b['style']['spacing']['padding']['left'] ?? '0'); ?>;
        --wp2s-padded-text-padding-right: <?php echo esc_attr($b['style']['spacing']['padding']['right'] ?? '0'); ?>;
        --wp2s-padded-text-padding-top: <?php echo esc_attr($b['style']['spacing']['padding']['top'] ?? '0'); ?>;
        --wp2s-padded-text-padding-bottom: <?php echo esc_attr($b['style']['spacing']['padding']['bottom'] ?? '0'); ?>;
    }
</style>