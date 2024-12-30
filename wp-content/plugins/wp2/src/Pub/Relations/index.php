<?php

$current_item = get_the_ID();

$relationships = get_post_taxonomies($current_item);

$relationships = array_filter($relationships, function($taxonomy) use ($current_item) {
	$terms = get_the_terms($current_item, $taxonomy);
	return is_array($terms) && count($terms) > 0;
});

?>

<?php if ( !empty($relationships)) : ?>

	<?php foreach ( $relationships as $relationship ) : ?>

		<div>

			<div>

				<?php
				$relationship_taxonomy = get_taxonomy($relationship);
				$relationship_taxonomy = str_replace('Related ', '', $relationship_taxonomy->labels->name);
				$relationship_taxonomy_slug = $relationship_taxonomy;
				$relationship_post_type = str_replace('shadow-', '', $relationship_taxonomy_slug);
				$relationship_taxonomy_page = get_page_by_path($relationship_taxonomy, OBJECT, $relationship_post_type);
				if ($relationship_taxonomy_page) {
					$relationship_taxonomy_page_url = get_permalink($relationship_taxonomy_page->ID);
				}
				?>

				<div>
					<?php echo $relationship_taxonomy; ?>
				</div>
				<?php
				$relationship_taxonomy_page_url = '';

				if ($relationship_taxonomy_page) {
					$relationship_taxonomy_page_url = get_permalink($relationship_taxonomy_page->ID);
				}
				?>

				<a href="<?php echo $relationship_taxonomy_page_url; ?>">
					<span class="screen-reader-text">
						<?php echo $relationship_taxonomy; ?>
					</span>
				</a>

			</div>

			<div>
				<?php
				$related_items_shadow_terms = get_the_terms($current_item, $relationship);

				$related_items_shadow_term_slugs = array_map(function($term) {
					return $term->slug;
				}, $related_items_shadow_terms);

				$related_items_taxonomy = get_taxonomy($relationship);

				$related_items_taxonomy_name = $related_items_taxonomy->name;

				$related_items = []; // Initialize the array

				foreach ($related_items_shadow_term_slugs as $related_items_shadow_term_slug) {	

					$related_item_post_type = str_replace('shadow-', '', $related_items_taxonomy_name);

					$related_items[] = get_page_by_path($related_items_shadow_term_slug, OBJECT, $related_item_post_type);

				}

				$related_items = array_filter($related_items, function($related_item) {
					return $related_item;
				});

				?>

				<div>

					<?php foreach ( $related_items as $related_item ) : ?>

						<?php
						$related_item_id = $related_item->ID;
						$related_item_title = $related_item->post_title;
						$related_item_url = get_permalink($related_item_id);
						?>

						<div>
							<a href="<?php echo $related_item_url; ?>">
								<?php echo $related_item_title; ?>
							</a>

						</div>

				
					<?php endforeach; ?>

					
				</div>
			
			</div>


		</div>

	<?php endforeach; ?>

<?php endif; ?>
