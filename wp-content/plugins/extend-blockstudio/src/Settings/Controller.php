<?php
// Path: wp-content/plugins/extend-blockstudio/src/Settings/Controller.php
namespace WP2\Extend\Blockstudio\Settings;

class Controller
{
    public function __construct()
    {
        add_filter('blockstudio/settings/users/ids', [$this, 'filter_user_ids']);
    }

    public function filter_user_ids($user_ids)
    {
        return array_merge($user_ids, defined('WP2S_BLOCKSTUDIO_USERS') ? WP2S_BLOCKSTUDIO_USERS : []);
    }
}
