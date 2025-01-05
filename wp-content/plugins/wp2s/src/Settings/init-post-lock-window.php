<?php
// Path: wp-content/plugins/wp2s/src/Settings/PostLockWindow/init.php
namespace WP2\Settings\PostLockWindow;

class Controller
{
    public function __construct()
    {
        add_filter('wp_check_post_lock_window', [$this, 'set_post_lock_window']);
    }

    /**
     * Set post lock window to 30 seconds.
     *
     * @param int $limit Default lock window (in seconds).
     * @return int Modified lock window.
     */
    public function set_post_lock_window($limit)
    {
        return 30;
    }
}

// Initialize the class.
new Controller();