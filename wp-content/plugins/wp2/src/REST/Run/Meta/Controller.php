<?php

namespace WP2\REST\Meta;

class MetaController
{
    const META_URL_KEY = '_oddnewsshow_url';
    const META_DATA_KEY = '_oddnewsshow_data';

    public function __construct()
    {
        add_action('init', [$this, 'register_meta_fields']);
    }

    public function register_meta_fields()
    {
        $object_types = ['post'];

        $args = [
            'type' => 'object',
            'description' => 'Metadata for snapshots.',
            'single' => true,
            'show_in_rest' => [
                'schema' => [
                    'type' => 'object',
                    'properties' => [
                        'snapshot_count' => ['type' => 'integer'],
                        'last_snapshot' => ['type' => 'string', 'format' => 'date-time'],
                        'snapshots' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'html' => ['type' => 'string'],
                                    'created_at' => ['type' => 'string', 'format' => 'date-time'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($object_types as $object_type) {
            register_meta($object_type, self::META_DATA_KEY, $args);
            register_meta($object_type, self::META_URL_KEY, [
                'type' => 'string',
                'description' => 'The URL associated with the content.',
                'single' => true,
                'show_in_rest' => true,
            ]);
        }
    }
}

new MetaController();