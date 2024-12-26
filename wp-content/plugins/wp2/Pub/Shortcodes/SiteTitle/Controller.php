<?php
add_shortcode('SiteTitle', function ($atts) {
	return get_bloginfo('name');
});
