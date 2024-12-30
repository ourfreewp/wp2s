<?php

function onthewater_get_the_post_thumbnail($post_id)
{
	$thumbnail = get_the_post_thumbnail($post_id, 'large', ['class' => 'object-fit-cover']);

	$thumbnail = '<figure class="wp-block-post-featured-image">' . $thumbnail . '</figure>';

	return $thumbnail;
}


function otw2_set_thumbnail_img_sizes() {

	// Sets the default thumbnail size.
	set_post_thumbnail_size( 480, 320, true );

	add_image_size( 'otw-thumb--sm', 320, 240, true ); // 4:3
	add_image_size( 'otw-thumb--lg', 800, 600, true ); // 4:3
	add_image_size( 'otw-video', 480, 270, true );     // 16:9
	add_image_size( 'otw-featured', 900, 600, true );  // 3:2
	add_image_size( 'otw-featured--hd', 1600, 1600, false ); // 21:9
	add_image_size( 'otw-cover', 480, 580, true );     // 12:14.5

	// Add 'thumbnail-name' => 'Thumbnail Display Name' to show options in post edit screens.
	add_filter( 'image_size_names_choose', function( $sizes ) {
		return array_merge( $sizes, [
			'otw-thumb--sm'    => '4:3 Small',
			'otw-thumb--lg'    => '4:3 Large',
			'otw-video'        => '16:9 Small',
			'otw-featured'     => 'Featured (3:2)',
		] );
	});
}
add_action( 'after_setup_theme', 'otw2_set_thumbnail_img_sizes' );


