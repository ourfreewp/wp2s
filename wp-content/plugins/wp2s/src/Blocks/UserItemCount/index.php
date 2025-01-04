<?php
namespace WP2S\Blocks\UserItemCount;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$count = $controller->get_user_post_count();
?>
<div useBlockProps class="wp2s-user-item-count">
    <?php echo esc_html($count); ?>
</div>