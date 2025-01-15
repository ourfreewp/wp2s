<?php
// Path: wp-content/plugins/wp2s/Blocks/StretchedLink/index.php

namespace WPS2\Blocks\StretchedLink;

$href = $attributes['link']['url'];

if ($isEditor) {
    $href = '#';
}

$title = $attributes['link']['title'];

?>

<a useBlockProps href="<?php echo esc_url($href); ?>" class="wp2s-stretched-link stretched-link">
    <span class="screen-reader-text"><?php echo esc_html($title); ?></span>
</a>
