<?php

WP2\Community\Services;

use WP2\Community\Helpers\Queries\MemberDetails;
use Exception;

/**
 * Service for synchronizing Bettermode member data with WordPress user meta.
 */
class MemberSyncService
{
    private string $graphqlUrl;
    private string $communityKey;

    /**
     * Constructor.
     *
     * @param string $graphqlUrl The GraphQL endpoint URL.
     * @param string $communityKey A unique community identifier for meta keys.
     * @throws Exception If the GraphQL URL is invalid.
     */
    public function __construct(string $graphqlUrl, string $communityKey)
    {
        if (empty($graphqlUrl) || !filter_var($graphqlUrl, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid GraphQL URL provided.");
        }

        $this->graphqlUrl = $graphqlUrl;
        $this->communityKey = $communityKey;
    }

    /**
     * Synchronizes Bettermode member data with WordPress user meta.
     *
     * @param string $memberId The Bettermode member ID.
     * @param int $userId The WordPress user ID.
     * @return void
     * @throws Exception If the synchronization fails.
     */
    public function syncMemberData(string $memberId, int $userId): void
    {
        // Prepare meta keys
        $lastSyncKey = "{$this->communityKey}_last_sync";
        $dataKey = "{$this->communityKey}_data";
        $subKey = "{$this->communityKey}_sub";

        // Early return if already synced
        $currentSub = get_user_meta($userId, $subKey, true);
        if ($currentSub === $memberId) {
            return;
        }

        $query = MemberDetails::get();

        $variables = [
            'id' => $memberId,
        ];

        try {
            $response = $this->makeGraphqlRequest($query, $variables);
        } catch (Exception $e) {
            error_log("Failed to sync member data: {$e->getMessage()}");
            throw $e;
        }

        $member = $response['data']['member'] ?? null;
        if (!$member) {
            throw new Exception("Bettermode member not found.");
        }

        // Update WordPress user meta
        update_user_meta($userId, $lastSyncKey, current_time('timestamp'));
        update_user_meta($userId, $dataKey, wp_json_encode($member));
        update_user_meta($userId, $subKey, $member['id']);
    }

    /**
     * Makes a GraphQL request to the Bettermode API.
     *
     * @param string $query The GraphQL query.
     * @param array $variables The query variables.
     * @return array The decoded response.
     * @throws Exception If the request fails.
     */
    private function makeGraphqlRequest(string $query, array $variables): array
    {
        $response = wp_remote_post($this->graphqlUrl, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => wp_json_encode([
                'query' => $query,
                'variables' => $variables,
            ]),
        ]);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            throw new Exception("GraphQL request failed: " . esc_html($error_message));
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['errors'])) {
            $error_details = esc_html(wp_json_encode($body['errors']));
            throw new Exception("GraphQL error: " . $error_details);
        }

        return $body;
    }
}
