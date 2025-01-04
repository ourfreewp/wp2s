<?php
namespace WP2S\Integrations\Iubenda\Klaviyo;

class Controller
{
    private $company_id;

    public function __construct()
    {
        $this->company_id = defined('WP2_KLAVIYO_COMPANY_ID') ? WP2_KLAVIYO_COMPANY_ID : '';
        add_action('wp_enqueue_scripts', [$this, 'add_iubenda_attributes'], 20);
    }

    public function add_iubenda_attributes()
    {
        if (!empty($this->company_id) && wp_script_is('klaviyo-script', 'enqueued')) {
            wp_script_add_data(
                'klaviyo-script',
                'attributes',
                [
                    'type' => 'text/plain',
                    'class' => '_iub_cs_activate',
                    'data-iub-purposes' => '2',
                    'data-suppressedsrc' => 'https://static.klaviyo.com/onsite/js/klaviyo.js?company_id=' . esc_attr($this->company_id)
                ]
            );
        }
    }
}

new Controller();