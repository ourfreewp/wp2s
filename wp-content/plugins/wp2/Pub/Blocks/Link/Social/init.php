<?php

function onthewater_fetch_social_link_data($name)
{

	$service = null;
	$icon = null;
	$label = null;

	switch ($name) {
		case 'twitter':
			$service = 'Twitter/X';
			$icon = 'twitter-x';
			$label = 'Twitter';
			break;
		case 'x':
			$service = 'Twitter/X';
			$icon = 'twitter-x';
			$label = 'Twitter';
			break;
		case 'facebook':
			$service = 'Facebook';
			$icon = 'facebook';
			$label = 'Facebook';
			break;
		case 'instagram':
			$service = 'Instagram';
			$icon = 'instagram';
			$label = 'Instagram';
			break;
		case 'youtube':
			$service = 'Youtube';
			$icon = 'youtube';
			$label = 'Youtube';
			break;
		default:
			$service = 'Link';
			$icon = 'link';
			$label = $name;
			break;
	}

	$icon_data = [
		'service' => $service,
		'icon'    => $icon,
		'label'   => $label,
	];
	
	return $icon_data;
}

function onthewater_fetch_social_link_icon($icon) {
	
}