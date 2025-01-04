<?php
namespace WP2S\Blocks\UserCover;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$photo = $controller->get_user_photo();
?>
<div useBlockProps class="wp2s-user-cover">
    <img src="<?php echo esc_url($photo); ?>" alt="">
</div>