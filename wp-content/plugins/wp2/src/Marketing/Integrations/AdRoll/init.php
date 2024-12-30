<?php

/**
 * Initializes the AdRoll Snippets
 */

namespace WP2S\AdRoll;

/**
 * Class Init
 *
 * Initializes the AdRoll Snippets.
 */

class Controller
{
    function __construct()
    {
        add_action('wp_head', array($this, 'head'));
    }

    /**
     * Adds the AdRoll snippet to the site.
     */

    public function head()
    {
        $head_file = __DIR__ . '/head.html';

        // get the contents of the head file
        $head = file_get_contents($head_file);

        // output the contents of the head in the wp_head hook
        echo $head;
    }
}

new Controller();
