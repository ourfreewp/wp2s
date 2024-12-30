<?php
function onthewater_get_the_breadcrumbs($taxonomy) {
	if (!shortcode_exists('slim_seo_breadcrumbs')) {
		return '';
	}

	$breadcrumbs = do_shortcode('[slim_seo_breadcrumbs taxonomy="' . $taxonomy . '"]');
	$breadcrumbs = onthewater_format_slimseo_breadcrumbs($breadcrumbs);
	return $breadcrumbs;
}


add_filter( 'slim_seo_breadcrumbs_args', function( $args ) {

	if ( is_singular( 'post' ) ) {
		$args['taxonomy'] = 'category';
	}
	
	if ( is_singular( 'article' ) || is_singular( 'slideshow' ) ) {
        $args['taxonomy'] = 'topic';
    }

    return $args;
} );


function onthewater_format_slimseo_breadcrumbs($breadcrumbs)
{

	if (empty($breadcrumbs)) {
		return '';
	}

	if (strpos($breadcrumbs, 'breadcrumbs__separator') != false) {

		$doc = new DOMDocument();

		libxml_use_internal_errors(true);
		$doc->loadHTML($breadcrumbs);
		libxml_use_internal_errors(false);

		$xpath = new DOMXPath($doc);

		$nodes = $xpath->query('//span[@class="breadcrumbs__separator"]');

		foreach ($nodes as $node) {
			$node->parentNode->removeChild($node);
		}

		$breadcrumbs = $doc->saveHTML();
	}

	$breadcrumb_tags = new WP_HTML_Tag_Processor($breadcrumbs);

	if ($breadcrumb_tags->next_tag(['class_name' => 'breadcrumbs'])) {

		$breadcrumb_tags->add_class('breadcrumb', true);
		$breadcrumb_tags->remove_class('breadcrumbs', true);

		while ($breadcrumb_tags->next_tag(['class_name' => 'breadcrumb'])) {
			$breadcrumb_tags->add_class('breadcrumb-item');
			$breadcrumb_tags->remove_class('breadcrumb');
			$breadcrumb_tags->remove_class('breadcrumb--first');
			$breadcrumb_tags->remove_class('breadcrumb--last');

			if ( 'A' === $breadcrumb_tags->get_tag() ) {

				// get href attribute

				$href = $breadcrumb_tags->get_attribute('href');

				// compare to home url

				$home_url = home_url() . '/';

				if ( $href == $home_url ) {

					$breadcrumb_tags->add_class('visually-hidden', true);

				}
			
			};
		}	

	}

	$breadcrumbs = $breadcrumb_tags->get_updated_html();

	return $breadcrumbs;

}
