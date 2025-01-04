<?php
namespace WP2S\Blocks\UserFirstName;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$first_name = $controller->get_user_first_name();
?>
<div useBlockProps class="wp2s-user-first-name">
    <?php echo esc_html($first_name); ?>
</div>