<?php

WP2\Community\Helpers\Queries;

/**
 * GraphQL query to search for a Bettermode member by email.
 */
class SearchMemberByEmail
{
    public static function get(): string
    {
        return <<<'GRAPHQL'
        query ($filterBy: [MemberListFilterByInput!]!, $limit: Int!) {
            members(filterBy: $filterBy, limit: $limit) {
                edges {
                    node {
                        id
                        username
                        email
                        name
                    }
                }
            }
        }
        GRAPHQL;
    }
}
