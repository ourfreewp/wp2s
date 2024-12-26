<?php


function nsp_unregister_greenshift_patterns() {
	unregister_block_pattern_category( 'greenshiftwireframes' );
	unregister_block_pattern_category( 'greenshiftelements' );
	unregister_block_pattern_category( 'greenshiftseoelements' );
	unregister_block_pattern_category( 'gspb_query' );
	unregister_block_pattern( 'greenshift/herocta' );
	unregister_block_pattern( 'greenshift/bannercta' );
	unregister_block_pattern( 'greenshift/ctaproductpanel' );
	unregister_block_pattern( 'greenshift/imagecssgrid' );
	unregister_block_pattern( 'greenshift/ctasixicons' );
	unregister_block_pattern( 'greenshift/categorygrid' );
	unregister_block_pattern( 'greenshift/infothreecolumn' );
	unregister_block_pattern( 'greenshift/stepcards' );
	unregister_block_pattern( 'greenshift/inforating' );
	unregister_block_pattern( 'greenshift/productfeat' );
	unregister_block_pattern( 'greenshift/infowithicons' );
	unregister_block_pattern( 'greenshift/infosteps' );
	unregister_block_pattern( 'greenshift/videofeatures' );
	unregister_block_pattern( 'greenshift/productcta' );
	unregister_block_pattern( 'greenshift/circlecloud' );
	unregister_block_pattern( 'greenshift/qasection' );
	unregister_block_pattern( 'greenshift/ideainfo' );
	unregister_block_pattern( 'greenshift/linedicons' );
	unregister_block_pattern( 'greenshift/videocta' );
	unregister_block_pattern( 'greenshift/fourcircleicons' );
	unregister_block_pattern( 'greenshift/pricetableblock' );
	unregister_block_pattern( 'greenshift/centralfeatures' );
	unregister_block_pattern( 'greenshift/ctathreeicons' );
	unregister_block_pattern( 'greenshift/ctablock' );
	unregister_block_pattern( 'greenshift/gridfour' );
	unregister_block_pattern( 'greenshift/circlelist' );
	unregister_block_pattern( 'greenshift/buttonone' );
	unregister_block_pattern( 'gspb_query/query/query-cover' );
	unregister_block_pattern( 'gspb_query/query/query-cover-simple' );
	unregister_block_pattern( 'gspb_query/query/query-wishlist' );
	unregister_block_pattern( 'gspb_query/query/query-syncslider' );
	unregister_block_pattern( 'gspb_query/query/query-hover' );
	unregister_block_pattern( 'gspb_query/query/query-prevnext' );
	unregister_block_pattern( 'greenshiftseo/versusthree' );
	unregister_block_pattern( 'greenshiftseo/versustwo' );
}

add_action( 'wp_loaded', 'nsp_unregister_greenshift_patterns' );
