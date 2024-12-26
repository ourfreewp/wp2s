import * as coda from "@codahq/packs-sdk";
import { BaseUrl } from "../constants/api";

export const NetworkParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Network",
    description: "The ID of the network in Bettermode",
    autocomplete: async function (context, search) {
        let url = `${BaseUrl}/graphql`;
        let query = `
      query ($limit: Int!, $query: String) {
        networks(limit: $limit, query: $query) {
          items {
            id
            name
          }
        }
      }
    `;

        let response = await context.fetcher.fetch({
            method: "POST",
            url: url,
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ query, variables: { limit: 10, query: search } }),
        });

        let results = response.body.data.networks.items;
        return coda.autocompleteSearchObjects(search, results, "name", "id");
    },
});