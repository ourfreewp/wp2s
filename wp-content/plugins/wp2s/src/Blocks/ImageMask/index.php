<?php
// Path: wp-content/plugins/wp2s/Blocks/ImageMask/index.php

namespace WPS2\Blocks\ImageMask;

?>

<div useBlockProps class="wp2s-image-mask">
<InnerBlocks template="<?php echo esc_attr( wp_json_encode([['core/image']])); ?>" />
</div>