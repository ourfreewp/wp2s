<?php

namespace FreeWP\Bettermode\Webhooks;

use WP_REST_Response;

class WebhookListener
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('freewp_async_bettermode_event', [$this, 'handle_async_webhook_event'], 10, 1);
    }

    // Register the REST route with WordPress
    public function register_routes()
    {
        register_rest_route('freewp-spaces/v1', '/webhook-events', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_webhook_event'],
            'permission_callback' => '__return_true',
        ]);
    }

    // Handle the webhook event and schedule for asynchronous processing
    public function handle_webhook_event($request)
    {
        $webhook_data = $request->get_json_params();

        if (!$this->validate_webhook_payload($webhook_data)) {
            return new WP_REST_Response(['message' => 'Invalid webhook data'], 400);
        }

        // Handle test verification by responding with the challenge value
        if (isset($webhook_data['type']) && $webhook_data['type'] === 'TEST') {
            $challenge = $webhook_data['data']['challenge'] ?? '';
            return new WP_REST_Response([
                'type' => 'TEST',
                'status' => 'SUCCEEDED',
                'data' => [
                    'challenge' => $challenge
                ]
            ], 200);
        }

        // Schedule asynchronous processing of actual webhook events
        $this->schedule_async_webhook_event($webhook_data);

        // Respond with a standard success message for non-test events
        return new WP_REST_Response([
            'type' => $webhook_data['type'] ?? 'UNKNOWN',
            'status' => 'SUCCEEDED',
            'data' => []
        ], 200);
    }

    // Validate webhook payload content
    private function validate_webhook_payload($webhook_data)
    {
        $valid_network_id = BETTERMODE_NETWORK_ID ?? '';

        return (
            isset($webhook_data['networkId']) &&
            $webhook_data['networkId'] === $valid_network_id
        );
    }

    // Schedule async processing of the webhook data
    private function schedule_async_webhook_event($webhook_data)
    {
        as_schedule_single_action(time(), 'freewp_async_bettermode_event', [$webhook_data]);
    }

    // Handle asynchronous webhook event processing
    public function handle_async_webhook_event($webhook_data)
    {
        $action_group = $this->set_action_scheduler_group($webhook_data);

        // Forward event to Klaviyo if applicable
        $this->forward_event_to_klaviyo($webhook_data);
    }

    // Define action scheduler group based on event type and verb
    private function set_action_scheduler_group($webhook_data)
    {
        $event_type = strtolower($webhook_data['type'] ?? '');
        $verb = strtolower($webhook_data['data']['verb'] ?? '');
        $verb_action = strtolower($webhook_data['data']['verbAction'] ?? '');

        return "{$event_type}.{$verb}.{$verb_action}";
    }

    // Forward event data to Klaviyo
    private function forward_event_to_klaviyo($webhook_data)
    {
        $klaviyo_data = [
            'event_name' => $webhook_data['data']['name'] ?? null,
            'user_email' => $webhook_data['data']['object']['email'] ?? null,
            'username' => $webhook_data['data']['object']['username'] ?? null,
            'additional_data' => $webhook_data
        ];

        // Placeholder for the actual API call to Klaviyo
        // KlaviyoService::sendEvent($klaviyo_data);
    }
}

// Initialize the listener
new WebhookListener();