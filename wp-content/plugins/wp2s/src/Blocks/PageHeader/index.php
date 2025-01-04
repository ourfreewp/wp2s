<?php
// Path: wp-content/plugins/wp2s/Blocks/PageHeader/index.php

namespace WPS2\Blocks\PageHeader;

$allowedBlocks = wp_json_encode(
    [
        'core/group', 
        'core/heading', 
        'core/paragraph'
    ]
);

?>

<header useBlockProps class="wp2s-page-header">
    <InnerBlocks allowedBlocks=<?php echo esc_attr($allowedBlocks); ?> />
</header>
