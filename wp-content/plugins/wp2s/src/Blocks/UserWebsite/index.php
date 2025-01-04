<?php
// Path: wp-content/plugins/wp2s/Blocks/UserWebsite/index.php

namespace WP2S\Blocks\UserWebsite;

use WP2S\Users\Controller as UserController;

$controller = new UserController();
$website    = $controller->get_user_website();

?>
<div useBlockProps class="wp2s-user-website">
    <a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a>
</div>