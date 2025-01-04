<?php
namespace WP2S\Blocks\UserFooter;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$footer = $controller->get_user_footer();
?>
<div useBlockProps class="wp2s-user-footer">
    <?php echo esc_html($footer); ?>
</div>