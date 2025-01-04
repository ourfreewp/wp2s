<?php
// Path: wp-content/plugins/wp2s/Blocks/UserRole/index.php

namespace WP2S\Blocks\UserRole;

use WP2S\Users\Controller as UserController;

$controller = new UserController();
$role       = $controller->get_user_role();

?>
<div useBlockProps class="wp2s-user-role">
    <?php echo esc_html($role); ?>
</div>