<?php
namespace WP2S\Blocks\UserBio;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$bio = $controller->get_user_bio();
?>
<div useBlockProps class="wp2s-user-bio">
    <?php echo esc_html($bio); ?>
</div>