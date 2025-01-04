<?php
namespace WP2S\Blocks\UserHeader;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$header = $controller->get_user_header();
?>
<div useBlockProps class="wp2s-user-header">
    <?php echo esc_html($header); ?>
</div>