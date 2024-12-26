<?php

WP2\Community\Services;

use WP2\Community\Helpers\Queries\LinkUserAccounts;
use Exception;

/**
 * Service for linking WordPress users to Bettermode accounts.
 */
class LinkUserAccountsService
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
     * Links a WordPress user to a Bettermode member by setting the externalId.
     *
     * @param string $memberId The Bettermode member ID.
     * @param int $userId The WordPress user ID.
     * @return void
     * @throws Exception If the linking fails.
     */
    public function linkUser(string $memberId, int $userId): void
    {
        // Early return if already linked
        $externalId = "{$this->communityKey}|{$userId}";
        $currentExternalId = get_user_meta($userId, "{$this->communityKey}_sub", true);
        if ($currentExternalId === $externalId) {
            return;
        }

        $query = LinkUserAccounts::get();

        $variables = [
            'id' => $memberId,
            'externalId' => $externalId,
        ];

        try {
            $response = $this->makeGraphqlRequest($query, $variables);
        } catch (Exception $e) {
            error_log("Failed to link user: {$e->getMessage()}");
            throw $e;
        }

        $updatedMember = $response['data']['updateMember']['member'] ?? null;
        if (!$updatedMember || $updatedMember['externalId'] !== $externalId) {
            throw new Exception("Failed to link user accounts.");
        }

        // Update WordPress user meta
        update_user_meta($userId, "{$this->communityKey}_sub", $externalId);
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
