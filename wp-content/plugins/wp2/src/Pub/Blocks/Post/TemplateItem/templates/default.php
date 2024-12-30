<?php
$term = isset($item['term']) ? $item['term'] : null;
$thumbnail = isset($item['thumbnail']) ? $item['thumbnail'] : null;
$title = isset($item['title']) ? $item['title'] : null;
$excerpt = isset($item['excerpt']) ? $item['excerpt'] : null;
$permalink = isset($item['permalink']) ? $item['permalink'] : null;
$byline = isset($item['byline']) ? $item['byline'] : null;
$dateline = isset($item['dateline']) ? $item['dateline'] : null;
$stretched_permalink = onthewater_get_stretched_link($permalink, $title, 'Continue Reading ', 'by ' . strip_tags($byline));

$show_header = !empty($term);
$show_media = !empty($thumbnail);
$show_footer = !empty($byline);

?>

<?php if ($show_media) : ?>
	<div class="wp-block-onthewater-post-template-media">

		<?php echo wp_kses_post($thumbnail); ?>

		<?php if (!$isEditor) : ?>
			<?php echo $stretched_permalink; ?>
		<?php endif; ?>

	</div>
<?php endif; ?>

<?php if ($show_header) : ?>
	<div class="wp-block-onthewater-post-template-header">
		<div class="wp-block-onthewater-post-template-terms">
			<?php if ($term) : ?>
				<?php echo wp_kses($term, ['div' => ['class' => true], 'a' => ['href' => true]]); ?>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<div class="wp-block-onthewater-post-template-body">

	<?php if ($title) : ?>
		<?php echo wp_kses($title, ['div' => ['class' => true]]); ?>
	<?php endif; ?>

	<?php if ($excerpt) : ?>
		<?php echo wp_kses($excerpt, ['div' => ['class' => true]]); ?>
	<?php endif; ?>



	<?php if (!$isEditor) : ?>
		<?php echo $stretched_permalink; ?>
	<?php endif; ?>

</div>

<?php if ($show_footer) : ?>
	<div class="wp-block-onthewater-post-template-footer">
		<?php if ($dateline) : ?>
			<?php echo $dateline; ?>
		<?php endif; ?>
		<?php echo wp_kses($byline, ['div' => ['class' => true], 'ul' => ['class' => true], 'li' => ['class' => true]], ['a' => ['href' => true, 'title' => true]]); ?>
	</div>
<?php endif; ?>