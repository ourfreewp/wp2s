<?php

function onthewater_get_the_term($post_id)
{

	$post_type = get_post_type($post_id);


	$term = null;

	switch ($post_type) {
		case 'post':

			if (is_category()) {
				$term = null;
			} else {
				$term = get_the_category($post_id);
			}

			break;

		case ('article' || 'slideshow'):

			if (is_tax('topic')) {
				$term = null;
			} else {
				$terms = get_the_terms($post_id, 'topic');
				if ($terms) {
					$term = $terms[0];
				}
			}

			break;
	}

	$term = sprintf(
		'<div class="wp-block-onthewater-post-term">
				<a href="%s">
					%s
				</a>
			</div>',
		esc_url(get_term_link($term)),
		$term->name
	);

	return $term;
}
