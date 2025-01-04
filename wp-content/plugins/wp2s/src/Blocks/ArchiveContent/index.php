<?php
// Path: wp-content/plugins/wp2s/Blocks/ArchiveContent/index.php

namespace WPS2\Blocks\ArchiveContent;

use WP2S\Singles\Archives\Controller as ArchivesController;

$controller = new ArchivesController();
$page_content = $controller->get_archive_content();

?>

<div useBlockProps class="wp2s-archive-content">
    <?php echo apply_filters('the_content', $page_content); ?>
</div>