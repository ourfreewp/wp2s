<?php
namespace WP2S\Blocks\UserLastName;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$last_name = $controller->get_user_last_name();
?>
<div useBlockProps class="wp2s-user-last-name">
    <?php echo esc_html($last_name); ?>
</div>