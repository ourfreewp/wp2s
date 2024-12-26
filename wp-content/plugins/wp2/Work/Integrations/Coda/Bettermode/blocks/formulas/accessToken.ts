// blocks/formulas/accessToken.ts
import * as coda from "@codahq/packs-sdk";
import { BaseUrl } from "../../constants/api";
import { ACCESS_TOKEN_QUERY } from "../../api/queries/accessToken";
import { MemberParameter } from "../../parameters/member";
import { NetworkParameter } from "../../parameters/network";

export const accessTokenFormulaConfig: coda.StringFormulaDef<[typeof NetworkParameter, typeof MemberParameter]> = {
  name: "AccessToken",
  description: "Retrieve an access token for the specified network and member.",
  parameters: [NetworkParameter, MemberParameter],
  resultType: coda.ValueType.String,
  execute: async function (
    [Network, Member]: [string, string],
    context: coda.ExecutionContext
  ) {
    const variables = { networkId: Network, entityId: Network, memberId: Member || "" };

    try {
      const response = await context.fetcher.fetch({
        url: BaseUrl,
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query: ACCESS_TOKEN_QUERY, variables }),
      });

      const accessToken = response.body.data?.limitedToken?.accessToken;

      if (!accessToken) {
        throw new Error("Access token could not be retrieved.");
      }

      return accessToken;

    } catch (error) {
      if (error instanceof Error) {
        throw new Error(`Failed to retrieve access token: ${error.message}`);
      } else {
        throw new Error("Failed to retrieve access token due to an unknown error.");
      }
    }
  },
};