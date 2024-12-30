<?php

function add_iubenda_script() {
    // Enqueue the JavaScript code for the frontend
    wp_enqueue_script('iubenda-script', 'https://cdn.iubenda.com/iubenda.js', array(), null, true);

    // Enqueue the JavaScript code for the admin area
    if (is_admin()) {
        wp_enqueue_script('iubenda-script-admin', 'https://cdn.iubenda.com/iubenda.js', array(), null, true);
    }
}

add_action('wp_enqueue_scripts', 'add_iubenda_script');
