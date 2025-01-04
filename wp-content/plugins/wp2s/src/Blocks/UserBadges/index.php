<?php
namespace WP2S\Blocks\UserBadges;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$badges = $controller->get_user_badges();
?>
<div useBlockProps class="wp2s-user-badges">
    <?php echo esc_html(json_encode($badges)); ?>
</div>