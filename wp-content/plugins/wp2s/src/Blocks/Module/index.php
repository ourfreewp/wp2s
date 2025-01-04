<?php
// Path: wp-content/plugins/wp2s/Blocks/Page/index.php

namespace WPS2\Blocks\Module;

?>

<InnerBlocks useBlockProps class="wp2s-module" template="<?php echo esc_attr( 
    wp_json_encode([
        [
            'core/paragraph',
            [
                'placeholder' => 'Add your content here',
            ],
        ],
    ])); 
?>" />


