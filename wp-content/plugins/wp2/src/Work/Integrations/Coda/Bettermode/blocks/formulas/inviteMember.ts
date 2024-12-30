// blocks/formulas/inviteMember.ts
import * as coda from "@codahq/packs-sdk";
import { BaseUrl } from "../../constants/api";
import { INVITE_MEMBER_MUTATION } from "../../api/mutations/inviteMember";
import { InviteeEmailParameter, InviteeNameParameter } from "../../parameters/invitee";
import { NetworkParameter } from "../../parameters/network";

export const inviteMemberFormulaConfig: coda.StringFormulaDef<[typeof NetworkParameter, typeof InviteeEmailParameter, typeof InviteeNameParameter]> = {
  name: "InviteMember",
  description: "Invite a new member to the community.",
  parameters: [NetworkParameter, InviteeEmailParameter, InviteeNameParameter],
  resultType: coda.ValueType.String,
  execute: async function (
    [Network, Email, Name]: [string, string, string],
    context: coda.ExecutionContext
  ) {
    const variables = { invitees: [{ email: Email, name: Name || "" }] };
    const response = await context.fetcher.fetch({
      url: BaseUrl,
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query: INVITE_MEMBER_MUTATION, variables }),
    });

    return response.body.data?.id || null;  // Use optional chaining and return null if not found
  },
};