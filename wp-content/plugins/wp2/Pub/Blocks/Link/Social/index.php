<?php
$attributes = isset($attributes) ? $attributes : [];

if (!$isEditor) {

	$user = get_queried_object();

	if (isset($attributes['userId'])) {
		$user = get_user_by('id', $attributes['userId']);
	}

	$user_id = $user->ID;

	$links = rwmb_meta('user_profile_links', ['object_type' => 'user'], $user_id);

	if (empty($links)) {
		return;
	}

	$icon_links = array_map(function ($link) {
		$name = explode('.', parse_url($link, PHP_URL_HOST))[0];
		$service_data = onthewater_fetch_social_link_data($name);
		return [
			'service' => $service_data['service'],
			'icon'    => $service_data['icon'],
			'domain'  => $name,
			'link'    => $link,
			'label'   => $service_data['label'],
		];
	}, $links);
} else {

	$domain_names = [
		'twitter',
		'facebook',
		'instagram',
		'youtube',
	];

	$icon_links = array_map(function ($name) {
		$service_data = onthewater_fetch_social_link_data($name);
		return [
			'service' => $service_data['service'],
			'icon'    => $service_data['icon'],
			'domain'  => $name,
			'link'    => 'javascript:void(0)',
			'label'   => $service_data['label'],
		];
	}, $domain_names);
}

?>

<ul useBlockProps>

	<?php foreach ($icon_links as $link) : ?>
		<li>
			<?php echo onthewater_fetch_social_link_icon($link['icon']); ?>
			
			<a href="<?php echo esc_url($link['link']); ?>" class="stretched-link">
				<span class="screen-reader-text"><?php echo esc_html($link['label']); ?></span>
			</a>
		</li>
	<?php endforeach; ?>

</ul>