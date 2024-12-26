<?php

WP2\Community\Services;

use WP2\Community\Helpers\Queries\SearchMemberByEmail;
use WP2\Community\Helpers\Queries\CreateMember;
use WP2\Community\Services\LinkUserAccountsService;
use Exception;

class MemberService
{
    private string $graphqlUrl;
    private LinkUserAccountsService $linkUserAccountsService;

    public function __construct(string $graphqlUrl, LinkUserAccountsService $linkUserAccountsService)
    {
        if (empty($graphqlUrl) || !filter_var($graphqlUrl, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid GraphQL URL provided.");
        }

        $this->graphqlUrl = $graphqlUrl;
        $this->linkUserAccountsService = $linkUserAccountsService;
    }

    public function searchMemberByEmail(string $email): ?array
    {
        $query = SearchMemberByEmail::get();
        $variables = [
            'filterBy' => [
                [
                    'key' => 'email',
                    'operator' => 'equals',
                    'value' => wp_json_encode($email),
                ],
            ],
            'limit' => 10,
        ];

        try {
            $response = $this->makeGraphqlRequest($query, $variables);
        } catch (Exception $e) {
            error_log('SearchMemberByEmail failed: ' . $e->getMessage());
            return null;
        }

        $edges = $response['data']['members']['edges'] ?? [];
        if (!empty($edges)) {
            $member = $edges[0]['node'];
            return [
                'id' => $member['id'],
                'username' => $member['username'],
                'email' => $member['email'],
                'name' => $member['name'],
            ];
        }

        return null;
    }

    public function createMember(string $email, string $name, int $userId): array
    {
        $mutation = CreateMember::get();
        $password = $this->generateSecurePassword();

        $variables = [
            'input' => [
                'email' => $email,
                'name' => $name,
                'password' => $password,
            ],
        ];

        try {
            $response = $this->makeGraphqlRequest($mutation, $variables);
        } catch (Exception $e) {
            throw new Exception('Failed to create member: ' . $e->getMessage());
        }

        $member = $response['data']['joinNetwork']['member'] ?? null;
        if (!$member) {
            throw new Exception("Failed to create member.");
        }

        $this->linkUserAccountsService->linkUser($member['id'], $userId);

        return [
            'id' => $member['id'],
            'username' => $member['username'],
            'email' => $member['email'],
            'name' => $name,
        ];
    }

    public function ensureMemberExists(string $email, string $name, int $userId): array
    {
        $existingMember = $this->searchMemberByEmail($email);

        if ($existingMember) {
            $this->linkUserAccountsService->linkUser($existingMember['id'], $userId);
            return $existingMember;
        }

        return $this->createMember($email, $name, $userId);
    }

    private function generateSecurePassword(int $length = 12): string
    {
        return wp_generate_password($length, true, true);
    }

    private function makeGraphqlRequest(string $query, array $variables): array
    {
        $response = wp_remote_post($this->graphqlUrl, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => wp_json_encode(['query' => $query, 'variables' => $variables]),
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
