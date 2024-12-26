<?php

WP2\Community\Helpers\Queries;

/**
 * GraphQL query to fetch detailed member information.
 */
class MemberDetails
{
    public static function get(): string
    {
        return <<<'GRAPHQL'
        query ($id: ID!) {
            member(id: $id) {
                id
                username
                email
                name
                displayName
                tagline
                profilePicture {
                    url
                }
                createdAt
                lastSeen
                status
                roles {
                    id
                    name
                    description
                }
            }
        }
        GRAPHQL;
    }
}
