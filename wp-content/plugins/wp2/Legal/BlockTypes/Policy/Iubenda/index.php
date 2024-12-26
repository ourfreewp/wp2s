<?php
$attributes = isset($attributes) ? $attributes : array();
$type       = isset($attributes['type']) ? $attributes['type'] : '';

$link = '';
$title = '';

switch ($type) {
	case 'terms':
		$link = 'https://www.iubenda.com/terms-and-conditions/87401701';
		$title = 'Terms and Conditions';
		break;
	case 'privacy':
		$link = 'https://www.iubenda.com/privacy-policy/87401701';
		$title = 'Privacy Policy';
		break;
	case 'cookies':
		$link = 'https://www.iubenda.com/privacy-policy/87401701/cookie-policy';
		$title = 'Cookie Policy';
		break;
	default:
		$link = '';
		$title = '';
		break;
}

?>

<div useBlockProps>
	<?php if (!$isEditor) : ?>
		<a title="<?php echo esc_attr($title); ?>" href="<?php echo esc_url($link); ?>" class="iubenda-nostyle no-brand iubenda-noiframe iubenda-embed iub-no-markup iubenda-noiframe iub-body-embed">
			<span class="screen-reader-text"><?php echo esc_html($title); ?></span>
		</a>
	<?php else : ?>
		<div id="iub-pp-container">
			<div id="iubenda_policy" class="iubenda_fluid_policy iubenda_embed_policy">
				<div id="wbars_all">
					<div class="iub_container iub_base_container">
						<div id="wbars">
							<div class="iub_content legal_pp">
								<h2>Dynamic Policy of Odd News Network</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nam libero justo laoreet sit amet cursus.</p>
								<p>Ante in nibh mauris cursus mattis molestie a iaculis. Nullam ac tortor vitae purus faucibus ornare. A arcu cursus vitae congue mauris rhoncus. Elit scelerisque mauris pellentesque pulvinar pellentesque habitant morbi. Odio morbi quis commodo odio.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>