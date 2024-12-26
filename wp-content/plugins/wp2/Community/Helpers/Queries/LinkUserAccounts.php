<?php

WP2\Community\Helpers\Queries;

/**
 * GraphQL mutation to link a WordPress user to a Bettermode member by setting externalId.
 */
class LinkUserAccounts
{
    public static function get(): string
    {
        return <<<'GRAPHQL'
        mutation ($id: ID!, $externalId: String!) {
            updateMember(input: { id: $id, externalId: $externalId }) {
                member {
                    id
                    externalId
                }
            }
        }
        GRAPHQL;
    }
}
