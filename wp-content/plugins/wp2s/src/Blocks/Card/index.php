<?php
// Path: wp-content/plugins/wp2s/Blocks/Card/index.php

namespace WP2S\Blocks\Card;

$card_layout = $attributes['layout'] ?? 1;
$card_object_type = $attributes['objectType'] ?? null;
$card_object = $attributes['object'] ?? null;
$call_to_action = $attributes['call_to_action'] ?? null;

$item = null;

if ($card_object === 'page') {
    $page = $attributes['page'] ?? null;
    $page_id = $page->ID ?? null;
    $item = get_post($page_id);
} if ($item) {
    $name = $item->post_title;
    $desc = $item->post_excerpt;
    $url  = get_permalink($item->ID);
    $url_title = 'Continue to ' . $name;
}


$show_footer = $call_to_action;


?>

<div useBlockProps class="wp2s-card wp2s-layout--<?php echo esc_attr($card_layout); ?> wp2s-card--<?php echo esc_attr($card_object); ?>">

    <div class="wp2s-card__inner">

        <div class="wp2s-card__content">

            <?php if ('page' === $card_object && $item) : ?>

                <div class="wp2s-name"><?php echo esc_html($name); ?></div>
                <div class="wp2s-description"><?php echo esc_html($desc); ?></div>

            <?php else : ?>

                <div class="wp2s-name">Placeholder</div>
                <div class="wp2s-description">This is a placeholder card.</div>

            <?php endif; ?>

        </div>

        <?php if ($show_footer) : ?>
            <div class="wp2s-card__footer">
                <div class="wp2s-card__actions">
                    <?php if ($call_to_action) : ?>
                        <?php echo esc_html($call_to_action); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php
        if (!$isEditor && $url) {
            echo bs_render_block([
                'id' => 'wp2s/stretched-link',
                'data' => [
                    'link' => [
                        'url' => $url,
                        'title' => $url_title,
                    ],
                ],
            ]);
        }
        ?>
    </div>
</div>