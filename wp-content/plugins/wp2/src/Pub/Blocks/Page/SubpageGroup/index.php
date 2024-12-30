<?php

$context_edit = isset($_GET['context']) && 'edit' == $_GET['context'];

$current_page_id = get_the_ID();

// if not a hierarchical post type, return

if (!is_post_type_hierarchical(get_post_type($current_page_id))) {
	return;
}

$subpages = get_pages(
	array(
		'post_type' => get_post_type($current_page_id),
		'sort_column' => 'order',
		'parent' => $current_page_id,
	)
);

?>
<?php if ( !$context_edit ) : ?>

	<?php if ( !empty( $subpages ) ) : ?>

		<div>

			<div>
				Subpages
			</div>

			<div>

				<?php foreach ($subpages as $subpage) : ?>

					<?php
					$subpage_id = $subpage->ID;
					$subpage_title = $subpage->post_title;
					$subpage_url = get_permalink($subpage_id);
					?>

					<div>

						<a href="<?php echo $subpage_url; ?>">
							<?php echo $subpage_title; ?>
						</a>

					</div>

				<?php endforeach; ?>
				
			</div>

		</div>

	<?php endif ?>

<?php else : ?>
	<div class="newsplicity-subpages">
		Not available on backend.
	</div>
<?php endif ?>