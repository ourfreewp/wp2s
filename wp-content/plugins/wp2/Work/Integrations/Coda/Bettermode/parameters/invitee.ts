import * as coda from "@codahq/packs-sdk";

export const InviteeEmailParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Email",
    description: "The email address of the invitee.",
});

export const InviteeNameParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Name",
    description: "The name of the invitee.",
    optional: true,
});