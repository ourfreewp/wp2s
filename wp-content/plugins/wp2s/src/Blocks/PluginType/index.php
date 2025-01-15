<?php
// Path: wp-content/plugins/wp2s/Blocks/PluginType/index.php

namespace WPS2\Blocks\PluginType;

global $wp_embed;

$prefix = 'wp2s_plugin_type_';
$type   = $attributes['type'] ?? null;

// type is a taxonomy term object

$name   = rwmb_meta($prefix . 'name') ?? null;
$desc   = rwmb_meta($prefix . 'description') ?? null;
$about  = rwmb_meta($prefix . 'about') ?? null;
$about  = do_shortcode(wpautop($wp_embed->autoembed($about)));

?>

<div useBlockProps class="wp2s-plugin-type">

    <div class="wp2s-plugin-type__inner">

        <div class="wp2s-section wp2s-section--wide grid">

            <div class="wp2s-plugin-type__content g-col g-col-12 g-col-md-6">
                <div class="wp2s-plugin-type__header">
                    <?php if ($name) : ?>
                        <p class="wp2s-plugin-type__name"><?php echo esc_html($name); ?></p>
                    <?php endif ?>
                    <?php if ($desc) : ?>
                        <p class="wp2s-plugin-type__description"><?php echo esc_html($desc); ?></p>
                    <?php endif ?>
                </div>
                <?php if ($about) : ?>
                    <div class="wp2s-plugin-type__about"><?php echo $about; ?></div>
                <?php endif ?>
                <?php if (!$block['postId'] === $post_id) : ?>
                    <div class="wp2s-plugin-type__footer">
                        <div class="wp2s-plugin-type__actions">
                            <?php if (!$block['postId'] === $post_id) : ?>
                                <a class="wp2s-plugin-type__action" href="<?php echo esc_url($permalink); ?>">
                                    Learn More
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>

    </div>

</div>