<?php

WP2\Community\Helpers\Queries;

/**
 * GraphQL mutation to create a new Bettermode member.
 */
class CreateMember
{
    public static function get(): string
    {
        return <<<'GRAPHQL'
        mutation ($input: JoinNetworkInput!) {
            joinNetwork(input: $input) {
                member {
                    id
                    username
                    email
                }
            }
        }
        GRAPHQL;
    }
}
