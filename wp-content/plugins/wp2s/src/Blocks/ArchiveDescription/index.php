<?php
// Path: wp-content/plugins/wp2s/Blocks/ArchiveDescription/index.php

namespace WPS2\Blocks\ArchiveDescription;

use WP2S\Singles\Archives\Controller as ArchivesController;

$controller = new ArchivesController();
$page_description = $controller->get_archive_description();

?>

<div useBlockProps class="wp2s-archive-description">
    <?php echo apply_filters('the_excerpt', $page_description); ?>
</div>