<?php

$current_object = get_queried_object();

// if current object is a post, get the post id

if (is_a($current_object, 'WP_Post')) {
	$current_post_id = $current_object->ID;
}

// get very ancestor of current post

$current_post_ancestors = get_post_ancestors($current_post_id);

?>

<div class="newsplicity-docs-menus">

	<div class="newsplicity-docs-menus-title">
		Docs
	</div>

	<?php
	$primary_pages = get_pages(
		array(
			'post_type' => 'doc',
			'parent' => 0,
			'sort_column' => 'menu_order, post_title',
			'sort_order' => 'ASC',
			'status' => 'publish',
		)
	);
	?>

	<?php if (!empty($primary_pages)) : ?>

		<nav class="newsplicity-docs-menu">

			<ul class="doc-pages is-primary-pages">

				<?php foreach ($primary_pages as $primary_page) : ?>

					<?php
					$primary_page_id = $primary_page->ID;
					$primary_page_title = $primary_page->post_title;
					$primary_page_short_title = get_post_meta($primary_page_id, 'newsplicity_short_title', true);
					$primary_page_short_title = trim($primary_page_short_title);
					if (!empty($primary_page_short_title)) {
						$primary_page_title = $primary_page_short_title;
					}
					$primary_page_url = get_permalink($primary_page_id);
					$primary_page_is_current = $current_post_id == $primary_page_id;

					// continue if primary page tile is empty after trimming
					if (empty($primary_page_title)) {
						continue;
					}

					?>

					<li class="doc-page">



						<a class="doc-page-link <?php echo esc_attr($primary_page_is_current ? 'is-current' : ''); ?>" href="<?php echo $primary_page_url; ?>">
							<?php echo $primary_page_title; ?>
						</a>



						<?php
						$secondary_pages = get_pages(
							array(
								'post_type' => 'doc',
								'parent' => $primary_page_id,
								'sort_column' => 'menu_order, post_title',
								'sort_order' => 'ASC',
							)
						);
						$contains_secondary_post = false;
						foreach ($secondary_pages as $secondary_page) {
							// see if current_post_ancestors contains secondary_page->ID
							if (in_array($secondary_page->ID, $current_post_ancestors)) {
								$contains_secondary_post = true;
							}
						}
						?>

						<?php if (!empty($secondary_pages)) : ?>

							<ul class="doc-pages details-content is-secondary-pages <?php echo esc_attr($contains_secondary_post ? 'contains-current-post' : ''); ?>">

								<?php foreach ($secondary_pages as $secondary_page) : ?>

									<?php
									$secondary_page_id = $secondary_page->ID;
									$secondary_page_title = $secondary_page->post_title;
									$secondary_page_short_title = get_post_meta($secondary_page_id, 'newsplicity_short_title', true);
									if (!empty($secondary_page_short_title)) {
										$secondary_page_title = $secondary_page_short_title;
									}
									$secondary_page_url = get_permalink($secondary_page_id);
									$secondary_page_is_current = $current_post_id == $secondary_page_id;
									?>

									<li class="doc-page">

										<a class="doc-page-link <?php echo esc_attr($secondary_page_is_current ? 'is-current' : ''); ?>" href="<?php echo $secondary_page_url; ?>">
											<?php echo $secondary_page_title; ?>
										</a>


										<?php
										$tertiary_pages = get_pages(
											array(
												'post_type' => 'doc',
												'parent' => $secondary_page_id,
												'sort_column' => 'menu_order, post_title',
												'sort_order' => 'ASC',
											)
										);
										$contains_tertiary_post = false;
										foreach ($tertiary_pages as $tertiary_page) {
											// see if current_post_ancestors contains tertiary_page->ID
											if (in_array($tertiary_page->ID, $current_post_ancestors)) {
												$contains_tertiary_post = true;
											}
										}
										?>

										<?php if (!empty($tertiary_pages)) : ?>

											<ul class="doc-pages details-content is-tertiary-pages <?php echo esc_attr($contains_tertiary_post ? 'contains-current-post' : ''); ?>">

												<?php foreach ($tertiary_pages as $tertiary_page) : ?>

													<?php
													$tertiary_page_id = $tertiary_page->ID;
													$tertiary_page_title = $tertiary_page->post_title;
													$tertiary_page_short_title = get_post_meta($tertiary_page_id, 'newsplicity_short_title', true);
													if (!empty($tertiary_page_short_title)) {
														$tertiary_page_title = $tertiary_page_short_title;
													}
													$tertiary_page_url = get_permalink($tertiary_page_id);
													$tertiary_page_is_current = $current_post_id == $tertiary_page_id;
													?>

													<li class="doc-page">



												
																<a class="doc-page-link <?php echo esc_attr($tertiary_page_is_current ? 'is-current' : ''); ?>" href="<?php echo $tertiary_page_url; ?>">
																	<?php echo $tertiary_page_title; ?>
																</a>
														

															<?php
															$quaternary_pages = get_pages(
																array(
																	'post_type' => 'doc',
																	'parent' => $tertiary_page_id,
																	'sort_column' => 'menu_order, post_title',
																	'sort_order' => 'ASC',
																)
															);
															$contains_quaternary_post = false;
															foreach ($quaternary_pages as $quaternary_page) {
																// dont need to check if current_post_ancestors contains quaternary_page->ID
																// because quaternary pages are the last level of the hierarchy
																if ($current_post_id == $quaternary_page->ID) {
																	$contains_quaternary_post = true;
																}
															}
															?>

															<?php if (!empty($quaternary_pages)) : ?>
																<ul class="doc-pages is-quaternary-pages <?php echo esc_attr($contains_quaternary_post ? 'contains-current-post' : ''); ?>">

																	<?php foreach ($quaternary_pages as $quaternary_page) : ?>

																		<?php
																		$quaternary_page_id = $quaternary_page->ID;
																		$quaternary_page_title = $quaternary_page->post_title;
																		$quaternary_page_short_title = get_post_meta($quaternary_page_id, 'newsplicity_short_title', true);
																		if (!empty($quaternary_page_short_title)) {
																			$quaternary_page_title = $quaternary_page_short_title;
																		}
																		$quaternary_page_url = get_permalink($quaternary_page_id);
																		$quaternary_page_is_current = $current_post_id == $quaternary_page_id;
																		?>

																		<li class="doc-page">
																			<a class="doc-page-link <?php echo esc_attr($quaternary_page_is_current ? 'is-current' : ''); ?>" href="<?php echo $quaternary_page_url; ?>">
																				<?php echo $quaternary_page_title; ?>
																			</a>
																		</li>

																	<?php endforeach; ?>

																</ul>
															<?php endif; ?>

												

													</li>

												<?php endforeach; ?>

											</ul>

										<?php endif; ?>



									</li>

								<?php endforeach; ?>

							</ul>

						<?php endif; ?>



					</li>

				<?php endforeach; ?>

			</ul>

		</nav>

	<?php endif; ?>

</div>