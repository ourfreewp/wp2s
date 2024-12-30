import * as coda from "@codahq/packs-sdk";

export const MemberParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Member",
    description: "The Id of the member.",
    optional: true
});