<?php

add_filter( 'gallery_style', function( $css ) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
});