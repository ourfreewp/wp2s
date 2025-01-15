<?php
// Path: wp-content/plugins/wp2s/src/Features/Services/init.php

namespace WP2S\Features\Services;

class Controller {

    private $features = WP2S_PLUGIN_DIR . 'src/Features/';
    private $services = WP2S_PLUGIN_DIR . 'src/Features/Services/';
    private $blocks   = WP2S_PLUGIN_DIR . 'src/Features/Blocks/';

}

new Controller();