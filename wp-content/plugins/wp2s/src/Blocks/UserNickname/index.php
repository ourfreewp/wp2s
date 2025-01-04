<?php
namespace WP2S\Blocks\UserNickname;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$nickname = $controller->get_user_nickname();
?>
<div useBlockProps class="wp2s-user-nickname">
    <?php echo esc_html($nickname); ?>
</div>