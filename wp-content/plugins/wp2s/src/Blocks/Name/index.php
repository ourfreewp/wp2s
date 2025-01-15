<?php
// Path: wp-content/plugins/wp2s/Blocks/Name/index.php

namespace WPS2\Blocks\Name;

$key = $a['attribute'] ?? 'name';
$allowedFormats = $a['allowedFormats'] ?? ['core/bold', 'core/italic'];
$placeholder = $a['placeholder'] ?? 'Enter Name';
$preserveWhiteSpace = $a['preserveWhiteSpace'] ?? false;
$tag = $a['tag'] ?? 'h1';
$withoutInteractiveFormatting = $a['withoutInteractiveFormatting'] ?? false;
?>

<RichText
    class="wp2s-name"
    <?php 
    if ($key) {
        echo 'attribute="' . esc_attr($key) . '" ';
    }
    if ($allowedFormats) {
        echo 'allowedFormats="' . esc_attr(wp_json_encode($allowedFormats)) . '" ';
    }
    if ($placeholder) {
        echo 'placeholder="' . esc_attr($placeholder) . '" ';
    }
    if (!is_null($preserveWhiteSpace)) {
        echo 'preserveWhiteSpace="' . esc_attr($preserveWhiteSpace ? 'true' : 'false') . '" ';
    }
    if ($tag) {
        echo 'tag="' . esc_attr($tag) . '" ';
    }
    if (!is_null($withoutInteractiveFormatting)) {
        echo 'withoutInteractiveFormatting="' . esc_attr($withoutInteractiveFormatting ? 'true' : 'false') . '" ';
    }
    ?>
/>