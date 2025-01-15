<?php
// Path: wp-content/plugins/wp2s/Blocks/Program/index.php

namespace WPS2\Blocks\Program;

$prefix = 'wp2s_program_';

$inherit = $attributes['inherit'] ?? false;
$program = $attributes['program'] ?? null;
$program_id = $program ? $program->ID : null;
$post_id = $block['context']['postId'] ?? null;

if ($inherit && $post_id) {
    $item = $post_id;
} elseif ($program_id) {
    $item = $program_id;
}

if ($item) {
    $program = get_post($item);
    $permalink =  !$isEditor ? get_the_permalink($program) : '#';
    $form    = rwmb_meta($prefix . 'form') ?? null;
    $name    = rwmb_meta($prefix . 'name') ?? null;
    $desc    = rwmb_meta($prefix . 'description') ?? null;


    global $wp_embed;
    $about   = rwmb_meta($prefix . 'about') ?? null;
    $about = do_shortcode(wpautop($wp_embed->autoembed($about)));
}

?>

<div useBlockProps class="wp2s-program">

    <div class="wp2s-program__inner">

        <div class="wp2s-section wp2s-section--wide grid">

            <div class="wp2s-program__content g-col g-col-12 g-col-md-6">
                <div class="wp2s-program__header">
                    <?php if ($name) : ?>
                        <p class="wp2s-program__name"><?php echo esc_html($name); ?></p>
                    <?php endif ?>
                    <?php if ($desc) : ?>
                        <p class="wp2s-program__description"><?php echo esc_html($desc); ?></p>
                    <?php endif ?>
                </div>
                <?php if ($about) : ?>
                    <div class="wp2s-program__about"><?php echo $about; ?></div>
                <?php endif ?>
                <?php if (!$block['postId'] === $post_id) : ?>
                    <div class="wp2s-program__footer">
                        <div class="wp2s-program__actions">
                            <?php if (!$block['postId'] === $post_id) : ?>
                                <a class="wp2s-program__action" href="<?php echo esc_url($permalink); ?>">
                                    Learn More
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="wp2s-program__form g-col g-col-12 g-col-md-6">
                <?php
                echo bs_block([
                    'id' => 'wp2-ws-form/form',
                    'data' => [
                        'ws_form' => esc_attr($form),
                    ],
                ]);
                ?>
            </div>
        </div>

    </div>

</div>