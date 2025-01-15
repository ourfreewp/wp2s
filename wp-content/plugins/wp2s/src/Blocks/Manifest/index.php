<?php
// Path: wp-content/plugins/wp2s/Blocks/Manifest/index.php

namespace WPS2\Blocks\Manifest;

$text_domain = 'wp2s';

$manifest = [];

$post_id = $b['postId'];
$post = get_post($post_id);
$post_type = $post->post_type;
$slug = $post->post_name;
$title = $post->post_title;
$description = $post->post_excerpt;

$manifest = [
    'id' => $post_id,
    'name' => $title,
    'slug' => $slug,
    'description' => $description,
    'type' => $post_type,
];
?>
<div class="wp2s-manifest">
    <div class="wp2s-manifest__inner">
        <?php
        echo bs_block([
            'id' => 'wps2/' . $manifest['slug'],
            'data' => [
                'id' => $manifest['id'],
                'name' => $manifest['name'],
                'slug' => $manifest['slug'],
                'description' => $manifest['description'],
                'type' => $manifest['type'],
            ]
        ]);
        ?>
    </div>
</div>
