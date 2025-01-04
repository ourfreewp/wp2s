<?php

// Path: wp-content/plugins/wp2s/Pixels/AdRoll/init.php
namespace WP2S\Pixels\AdRoll;

class Controller
{
    private $handle = 'blockstudio-wp2s-pixel-adroll-data';
    private $object_name = 'wp2s_pixels_adroll';

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'inject_adroll_data']);
    }

    public function inject_adroll_data()
    {
        // Ensure the constants are defined
        if (!defined('ADROLL_ADVERTISER_ID') || !defined('ADROLL_PIXEL_ID')) {
            error_log('AdRoll Pixel: Missing ADROLL_ADVERTISER_ID or ADROLL_PIXEL_ID');
            return;
        }

        // Prepare AdRoll pixel data
        $adroll_data = [
            [
                'advertiserId' => ADROLL_ADVERTISER_ID,
                'pixelId'      => ADROLL_PIXEL_ID,
                'version'      => '2.0',
            ],
        ];

        // Register an empty script
        wp_register_script($this->handle, '', [], null, true);

        // Localize the script with AdRoll data
        wp_localize_script($this->handle, $this->object_name, [
            'pixels' => [
                'adroll' => $adroll_data
            ]
        ]);

        // Enqueue the script (empty, but injects data)
        wp_enqueue_script($this->handle);
    }
}

// Instantiate the class to ensure the hook runs
new Controller();