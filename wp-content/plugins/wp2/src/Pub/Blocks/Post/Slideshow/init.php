<?php

function oddnews_get_last_slide_data($post_id, $slide_count)
{

	$last_slide = [];

	$slide_field_key = 'slideshow-slide';

	$last_slide_key = 'slideshow-last-slide';

	$last_slide_config = rwmb_get_value($last_slide_key, [], $post_id);

	if (!empty($last_slide_config)) {

		$last_slide_header = isset($last_slide_config[$last_slide_key . '-header']) ? $last_slide_config[$last_slide_key . '-header'] : [];

		$last_slide_toggle = isset($last_slide_header[$last_slide_key . '-toggle']) ? $last_slide_header[$last_slide_key . '-toggle'] : false;

		$last_slide_toggle_custom = isset($last_slide_header[$last_slide_key . '-toggle-custom']) ? $last_slide_header[$last_slide_key . '-toggle-custom'] : false;

		if ($last_slide_toggle) {

			if (!$last_slide_toggle_custom) {

				$default_last_slide = rwmb_meta('slideshow-last-slide-defaults', ['object_type' => 'setting'], 'slideshows-settings-page');

				$last_slide = [
					'title' => $default_last_slide[$last_slide_key . '-title'] ? $default_last_slide[$last_slide_key . '-title'] : '',
					'content' => $default_last_slide[$last_slide_key . '-content'] ? $default_last_slide[$last_slide_key . '-content'] : '',
					'image' => $default_last_slide[$last_slide_key . '-image'][0] ? $default_last_slide[$last_slide_key . '-image'][0] : '',
					'image_thumbnail' => $default_last_slide[$last_slide_key . '-image-thumbnail'][0] ? $default_last_slide[$last_slide_key . '-image-thumbnail'][0] : '',
					'position' => $slide_count + 1,
				];

			} else {

				$last_slide_content = isset($last_slide_config[$last_slide_key . '-content']) ? $last_slide_config[$last_slide_key . '-content'] : [];

				$last_slide_content_image = isset($last_slide_content[$slide_field_key . '-image']) ? $last_slide_content[$slide_field_key . '-image'] : [];

				$last_slide_content_thumbnail = isset($last_slide_content[$slide_field_key . '-image-thumbnail']) ? $last_slide_content[$slide_field_key . '-image-thumbnail'] : [];

				$last_slide_content_title = isset($last_slide_content[$slide_field_key . '-title']) ? $last_slide_content[$slide_field_key . '-title'] : [];

				$last_slide_content_content = isset($last_slide_content[$slide_field_key . '-content']) ? $last_slide_content[$slide_field_key . '-content'] : [];

				$last_slide = [
					'title' => $last_slide_content_title,
					'content' => $last_slide_content_content,
					'image' => $last_slide_content_image[0],
					'image_thumbnail' => $last_slide_content_thumbnail[0],
					'position' => $slide_count + 1,
				];

			}


		} else {

			$last_slide = [];

		}
	}

	return $last_slide;
}