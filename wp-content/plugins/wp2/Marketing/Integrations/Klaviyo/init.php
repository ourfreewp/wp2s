<?php
// Path: wp-content/plugins/wp2/Marketing/Integrations/Klaviyo/Controller.php

namespace WP2\Marketing\Integrations\Klaviyo;

class Controller
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        add_action('wp_head', [$this, 'klaviyo_user_identified_script'], 99);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_klaviyo_onsite_script']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_klaviyo_onsite_script']);
        add_action('phpmailer_init', [$this, 'send_mail_data_to_klaviyo']);
    }

    public function consent_solution_check()
    {
        return is_plugin_active('iubenda-cookie-law-solution/iubenda-cookie-law-solution.php');
    }

    public function klaviyo_user_identified_script()
    {
        if (is_user_logged_in() && !isset($_SESSION['klaviyo_user_identified'])) {
            $current_user = wp_get_current_user();
            $email = $current_user->user_email;

            $script = '
            <script data-iub-cs-wait-for="klaviyo" class="_iub_cs_activate-inline" data-iub-purposes="3">
                var _learnq = _learnq || [];
                _learnq.push(["identify", {
                    "$email" : "' . esc_js($email) . '"
                }]);
                if (typeof fathom !== "undefined") {
                    fathom.trackGoal("M1G0EN5A", 0);
                }
                console.log("Klaviyo User Identified");
            </script>
            ';

            echo $script;
            $_SESSION['klaviyo_user_identified'] = true;
        }
    }

    public function enqueue_klaviyo_onsite_script()
    {
        $klaviyo_company_id = rwmb_meta('klaviyo_company_id', ['object_type' => 'setting'], 'newsplicity-settings');

        if (!empty($klaviyo_company_id)) {
            wp_enqueue_script(
                'klaviyo-script',
                'https://static.klaviyo.com/onsite/js/klaviyo.js?company_id=' . esc_attr($klaviyo_company_id),
                [],
                false,
                true
            );

            wp_script_add_data(
                'klaviyo-script',
                'attributes',
                [
                    'type' => 'text/plain',
                    'class' => '_iub_cs_activate',
                    'data-iub-purposes' => '2',
                    'data-suppressedsrc' => 'https://static.klaviyo.com/onsite/js/klaviyo.js?company_id=' . esc_attr($klaviyo_company_id)
                ]
            );
        }
    }

    public function send_mail_data_to_klaviyo($phpmailer)
    {
        foreach ($phpmailer->getToAddresses() as $to_address) {
            $email_data = [
                'data' => [
                    'type' => 'event',
                    'attributes' => [
                        'properties' => [
                            'to' => $to_address,
                            'subject' => $phpmailer->Subject,
                            'message' => $phpmailer->Body,
                            'headers' => $phpmailer->getCustomHeaders(),
                            'attachments' => $phpmailer->getAttachments(),
                        ],
                        'metric' => [
                            'data' => [
                                'type' => 'metric',
                                'attributes' => [
                                    'name' => 'Email Initiated',
                                    'service' => 'WordPress',
                                ],
                            ],
                        ],
                        'profile' => [
                            'data' => [
                                'type' => 'profile',
                                'attributes' => [
                                    'email' => $to_address[0],
                                ],
                            ],
                        ],
                        'unique_id' => wp_generate_uuid4(),
                        'time' => date('Y-m-d\TH:i:s\Z'),
                    ],
                ],
            ];

            $klaviyo_api_key = rwmb_meta('klaviyo_private_api', ['object_type' => 'setting'], 'klaviyo');
            $request_body = json_encode($email_data);

            wp_remote_post('https://a.klaviyo.com/api/events/', [
                'method' => 'POST',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => '*/*',
                    'Authorization' => 'Klaviyo-API-Key ' . esc_attr($klaviyo_api_key),
                ],
                'body' => $request_body
            ]);

            $phpmailer->ClearAllRecipients();
            wp2docs_trigger_automation("wordpress-email-sent", $email_data);
        }
    }
}
