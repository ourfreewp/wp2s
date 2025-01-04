<?php
// Path: wp-content/plugins/wp2s/Blocks/ArchiveName/index.php

namespace WPS2\Blocks\ArchiveName;

use WP2S\Singles\Archives\Controller as ArchivesController;

$controller = new ArchivesController();
$page_title = $controller->get_archive_name();

?>

<div useBlockProps class="wp2s-archive-name">
    <?php echo apply_filters('the_title', $page_title); ?>
</div>