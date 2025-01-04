<?php
// Path: wp-content/plugins/wp2s/Blocks/Page/index.php

namespace WPS2\Blocks\Page;

?>



<InnerBlocks useBlockProps class="wp2s-page" template="<?php echo esc_attr( 
    wp_json_encode([
        [
            'wp2s/page-header'
        ],
        [
            'wp2s/page-section'
        ]
    ])); 
?>" />


