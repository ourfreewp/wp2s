<?php

add_filter('rwmb_meta_boxes', function ($field_groups) {

	$post_types = get_post_types([
		'public' => true
	]);

	$post_exclude_everywhere = [
		'id'    => 'exclude-everywhere',
		'type'  => 'switch',
		'style' => 'rounded',
		'on_label'  => 'Yes',
		'off_label' => 'No',
	];

	$field_groups[] = [
		'title'      => 'Exclude Everywhere',
		'id'         => 'settings-exclude-everywhere',
		'post_types' => $post_types,
		'context'    => 'side',
		'priority'   => 'low',
		'closed'     => true,
		'autosave'   => true,
		'fields' => [
			$post_exclude_everywhere,
		],
		'include' => [
			'user_role' => ['administrator', 'editor'],
		]
	];

	return $field_groups;

});


add_action('save_post', function ($post_id) {
	
	$parent_id = wp_is_post_revision($post_id);

	if (false !== $parent_id) {
		$post_id = $parent_id;
	}

	$attribute_taxonomy = 'attribute';

	if (!taxonomy_exists($attribute_taxonomy)) {
		return;
	}

	$exclude_everywhere = rwmb_meta('exclude-everywhere', '', $post_id);

	$exclude_everywhere_on_attribute = 'exclude-everywhere';

	$exclude_everywhere_off_attribute = 'included-somewhere';

	if ($exclude_everywhere === '1') {
		wp_set_object_terms($post_id, $exclude_everywhere_on_attribute, $attribute_taxonomy, true);
		wp_remove_object_terms($post_id, $exclude_everywhere_off_attribute, $attribute_taxonomy);
	} else {
		wp_set_object_terms($post_id, $exclude_everywhere_off_attribute, $attribute_taxonomy, true);
		wp_remove_object_terms($post_id, $exclude_everywhere_on_attribute, $attribute_taxonomy);
	}

});
