<?php

$current_post_id = isset($block['postId']) ? $block['postId'] : '';

$context_post_id = isset($block['context']['postId']) ? $block['context']['postId'] : '';

$avatar_default = isset($a['avatarDefault']) ? $a['avatarDefault'] : 'wavatar';

$avatar_size = isset($a['avatarSize']) ? $a['avatarSize'] : 96;

$post_id = $context_post_id ? $context_post_id : $current_post_id;

if (function_exists('get_coauthors')) {
	$coauthors = get_coauthors($post_id);
} else {
	$coauthors = get_users(['role' => 'author', 'number' => 3, 'orderby' => 'rand']);
}

?>

<?php if (!empty($coauthors)) : ?>

	<ul useBlockProps>

		<?php foreach ($coauthors as $author) : ?>

			<?php
			$author_id = $author->ID;
			$avatar_link = get_author_posts_url($author_id);
			?>

			<li class="wp-block-onthewater-post-author-avatar">
				<?php echo wp_kses(get_simple_local_avatar($author_id, $avatar_size, $default_value = $avatar_default), 'post'); ?>
				<?php if (!$isEditor) : ?>
					<a href="<?php echo esc_url($avatar_link); ?>" class="stretched-link">
						<span class="screen-reader-text">Continue to posts by <?php echo esc_html($author->display_name); ?></span>
					</a>
				<?php endif; ?>

			</li>

		<?php endforeach; ?>

	</ul>

<?php endif; ?>