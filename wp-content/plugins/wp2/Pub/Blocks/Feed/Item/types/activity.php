<?php
$post_id = isset($block['postId']) ? $block['postId'] : '';
$post = get_post($post_id);
?>

<div useBlockProps>
    <?php echo apply_filters('the_content', $post->post_content); ?>
</div>
