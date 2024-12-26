<?php

/**
 * Studio block.
 *
 * @package wp2-studio
 */

$post_id = isset($post_id) ? $post_id : '';

$post = get_post($post_id);

$prefix = 'wp2-studio-';

$type = '';

if (isset($post->post_name) && strpos($post->post_name, $prefix) === 0) {

    $type = substr($post->post_name, strlen($prefix));
}

$placeholder = ucfirst($type) . ' Studio';

?>

<div useBlockProps class="wp2-studio" tag="main">

    <div class="wp2-studio__inner">

        <header class="wp2-studio__header">
            <h1 class="wp2-studio__title"><?php echo esc_html($placeholder); ?></h1>
        </header>

        <div class="wp2-studio__body">

            <section class="wp2-studio__content">
                Content Placeholder
            </section>

        </div>

        <footer class="wp2-studio__footer">
            <div class="wp2-studio__footer-inner">
                Footer Placeholder
            </div>
        </footer>

    </div>

</div>