<?php
// Path: wp-content/plugins/wp2s/Blocks/Description/index.php

namespace WPS2\Blocks\Description;
?>
<div useBlockProps class="wp2s-description">
    <RichText 
        attribute="description" 
        allowedFormats="<?php echo esc_attr(wp_json_encode(['core/bold', 'core/italic'])); ?>" 
        placeholder="Enter Description"
        preserveWhiteSpace="true"
        tag="div"
        withoutInteractiveFormatting="true"
    />
</div>