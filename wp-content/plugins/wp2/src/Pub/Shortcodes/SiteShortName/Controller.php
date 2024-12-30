<?php
add_shortcode('SiteShortName', function ($atts) {
	return get_bloginfo('short_name');
});
