<?php
namespace WP2S\Blocks\UserDisplayName;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$display_name = $controller->get_user_display_name();
?>
<div useBlockProps class="wp2s-user-display-name">
    <?php echo esc_html($display_name); ?>
</div>