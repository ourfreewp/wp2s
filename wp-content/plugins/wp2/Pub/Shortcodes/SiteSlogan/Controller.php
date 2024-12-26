<?php
add_shortcode('SiteTagline', function ($atts) {
	return get_bloginfo('blogdescription');
});
