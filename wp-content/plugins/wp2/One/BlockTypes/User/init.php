<?php
add_filter( 'rwmb_meta_boxes', 'oddnews_custom_template_user_page_settings' );

function oddnews_custom_template_user_page_settings( $meta_boxes ) {
    $prefix = 'user_page_';

    $meta_boxes[] = [
        'title'      => __( 'Form', 'oddnews' ),
        'post_types' => ['page'],
		'id'         => $prefix . 'settings',
        'context'    => 'side',
        'closed'     => true,
        'include'    => [
            'relation' => 'AND',
            'template' => ['custom-template-user-page'],
        ],
        'fields'     => [
            [
                'id'      => $prefix . 'form_id',
                'type'    => 'select_advanced',
                'options' => wsf_form_get_all_key_value(),
            ],
        ],
    ];

    return $meta_boxes;
}