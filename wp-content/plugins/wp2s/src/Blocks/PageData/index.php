<?php
// Path: wp-content/plugins/wp2s/Blocks/PageData/index.php

namespace WPS2\Blocks\PageData;

if (!$isEditor) {
    return;
}

?>
<div useBlockProps class="wp2s-page-data">

    <InnerBlocks template="<?php echo esc_attr(
        wp_json_encode([
            [
                'core/post-excerpt'
            ],
        ])
    );
    ?>" 
    templateLock="all"
    />
</div>