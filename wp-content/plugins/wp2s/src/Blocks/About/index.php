<?php
// Path: wp-content/plugins/wp2s/Blocks/About/index.php

namespace WPS2\Blocks\About;
$tag = $a['tag'] ?? 'div';
?>

<InnerBlocks
    className="wp2s-about"
    useBlockProps
    tag="<?php echo esc_attr($tag); ?>"
/>