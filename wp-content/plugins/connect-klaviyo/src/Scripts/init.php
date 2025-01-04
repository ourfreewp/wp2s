<?php
namespace WP2\Connect\Klaviyo\Scripts;

class Controller
{
    private $company_id;

    public function __construct()
    {
        $this->company_id = defined('WP2_KLAVIYO_COMPANY_ID') ? WP2_KLAVIYO_COMPANY_ID : '';
        add_action('wp_enqueue_scripts', [$this, 'enqueue_klaviyo_onsite_script']);
    }

    public function enqueue_klaviyo_onsite_script()
    {
        if (!empty($this->company_id)) {
            wp_enqueue_script(
                'klaviyo-script',
                'https://static.klaviyo.com/onsite/js/klaviyo.js?company_id=' . esc_attr($this->company_id),
                [],
                false,
                true
            );
        }
    }
}

new Controller();