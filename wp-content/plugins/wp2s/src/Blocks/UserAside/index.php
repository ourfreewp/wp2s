<?php
namespace WP2S\Blocks\UserAside;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$bio = $controller->get_user_bio();
?>
<div useBlockProps class="wp2s-user-aside">
    <?php echo esc_html($bio); ?>
</div>