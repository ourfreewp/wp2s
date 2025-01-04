<?php
namespace WP2S\Blocks\UserPhoto;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$photo = $controller->get_user_photo();
?>
<div useBlockProps class="wp2s-user-photo">
    <img src="<?php echo esc_url($photo); ?>" alt="">
</div>