// config/auth.ts
import * as coda from "@codahq/packs-sdk";

export function setAuthentication(pack: coda.PackDefinitionBuilder) {
  pack.setUserAuthentication({
    type: coda.AuthenticationType.WebBasic,
    instructionsUrl: "https://developers.bettermode.com/docs/guide/",
    uxConfig: {
      placeholderUsername: "Client Id",
      placeholderPassword: "Client Secret",
    },
  });
}