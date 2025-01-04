<?php
namespace WP2S\Blocks\UserCollection;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$collection = $controller->get_user_collection();
?>
<div useBlockProps class="wp2s-user-collection">
    <?php echo esc_html(json_encode($collection)); ?>
</div>