<?php

/**
 * Outputs html for post subtitle if it has one.
 *
 * @param string $classname - optionally change classname for outputted $wrapper element.
 */
function otw2_subtitle( $classname = 'Content-subtitle' ) {
	$subtitle = get_post_meta( get_the_ID(), '_otw_post_subtitle', true );
	if ( '' !== $subtitle ) {
		printf( '<p class="%1$s">%2$s</p>', esc_attr( $classname ), esc_html( $subtitle ) );
	} else {
		return '';
	}
}
