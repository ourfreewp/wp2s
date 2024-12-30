<?php

$template = wp_json_encode([[
    [
        'core/query',
        [
            'query' => [
                'inherit' => true,
            ],
            'enhancedPagination' => true,
        ],
        [
            'core/post-template',
            [],
            [
                'freewp/activity-feed-item'
            ],
            [
                'core/query-pagination',
                [
                    'core/query-pagination-previous',
                    'core/query-pagination-next',
                ],
            ],
            [
                'core/query-no-results',
                [
                    'freewp/activity-feed-no-results',
                    [],
                ],
            ],
        ],
    ],
]]);
?>

<div useBlockProps>
    <InnerBlocks
        template=<?php echo esc_attr($template); ?> 
        templateLock="contentOnly"
    />
</div>