<?php

add_filter( 'altis.analytics.data.endpoint', function ( $endpoint ) {
	
    $attributes = [];

	$user = wp_get_current_user();
	
	$paid = $user->get('member');

	if ($paid) {
		$attributes['member'] = 1; // User is member
	} else {
		$attributes['member'] = 0; // User is not member
	}
	
    $endpoint['Attributes'] = $attributes;

    return $endpoint;
});

// Register the 'member' field
Altis\Accelerate\Audiences\register_field(
    'endpoint.Attributes.member',
    __( 'Member' ),
    [
        'column' => "endpoint['attributes']['member']",
    ]
);


add_filter( 'altis.analytics.data.endpoint', function ( $endpoint ) {
	
    $attributes = [];

	$access_levels = get_the_terms(get_the_ID(), 'access-level');

	if ($access_levels && !is_wp_error($access_levels)) {
		$term = reset($access_levels); // Get the first term in the array
		$attributes['access_level'] = $term ? $term->slug : 'visitor'; // Set to 'visitor' if no term is found
	} else {
		$attributes['access_level'] = 'visitor'; // Default value if no access levels are found
	}

    // Add the attributes to the endpoint
    $endpoint['Attributes'] = $attributes;

    return $endpoint;
});

// Register the 'Access Level' field
Altis\Accelerate\Audiences\register_field(
    'endpoint.Attributes.access_level',
    __( 'Access Level' ),
    [
        'column' => "endpoint['attributes']['access_level']",
    ]
);


add_filter( 'altis.analytics.data.endpoint', function ( $endpoint ) {
	
    $attributes = [];

	$user = wp_get_current_user();
	
	$shopify_tags = $user->get('shopify_tags');

	if ($shopify_tags) {
		$attributes['shopify_tags'] = $shopify_tags;
	}
	
    $endpoint['Attributes'] = $attributes;

    return $endpoint;
});

// Register the 'Shopify Tags' field
Altis\Accelerate\Audiences\register_field(
    'endpoint.Attributes.shopify_tags',
    __( 'Shopify Tags' ),
    [
        'column' => "endpoint['attributes']['shopify_tags']",
    ]
);
