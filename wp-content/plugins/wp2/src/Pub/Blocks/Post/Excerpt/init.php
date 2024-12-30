<?php
// Set Excerpt Length
add_filter( 'excerpt_length', function( $length ) {
	return 35;
}, 999 );

// Limits the excerpt first full sentence.
add_filter( 'get_the_excerpt', function( $excerpt ) {
	$allowed_ends = [ '.', '!', '?', '...' ];
	$number_sentences = 1;
	$excerpt_chunk = $excerpt;
	for ( $i = 0; $i < $number_sentences; $i++ ) {
		$lowest_sentence_end[ $i ] = 100000000000000000;
		foreach ( $allowed_ends as $allowed_end ) {
			$sentence_end = strpos( $excerpt_chunk, $allowed_end );
			if ( false !== $sentence_end && $sentence_end < $lowest_sentence_end[ $i ] ) {
				$lowest_sentence_end[ $i ] = $sentence_end + strlen( $allowed_end );
			}
			$sentence_end = false;
		}
		$sentences[ $i ] = substr( $excerpt_chunk, 0, $lowest_sentence_end[ $i ] );
		$excerpt_chunk = substr( $excerpt_chunk, $lowest_sentence_end[ $i ] );
	}
	return implode( '', $sentences );
});

// Remove Ellipsis
add_filter( 'excerpt_more', function( $more ) {
	return '';
});


function onthewater_get_the_excerpt($post_id)
{
	$excerpt = get_the_excerpt($post_id);

	$excerpt = '<div class="wp-block-post-excerpt">' . $excerpt . '</div>';

	return $excerpt;
}