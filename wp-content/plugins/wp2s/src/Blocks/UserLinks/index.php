<?php
namespace WP2S\Blocks\UserLinks;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$links = $controller->get_user_links();
?>
<div useBlockProps class="wp2s-user-links">
    <?php echo esc_html(json_encode($links)); ?>
</div>