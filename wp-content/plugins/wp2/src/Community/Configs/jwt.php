<?php
return [
    'private_key' => defined('WP_COMMUNITY_JWT_BETTERMODE_PRIVATE_KEY') ? WP_COMMUNITY_JWT_BETTERMODE_PRIVATE_KEY : '',
    'algorithm' => defined('WP_COMMUNITY_JWT_BETTERMODE_ALGORITHM') ? WP_COMMUNITY_JWT_BETTERMODE_ALGORITHM : 'HS256',
];