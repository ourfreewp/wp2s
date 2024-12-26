export const ACCESS_TOKEN_QUERY = `
  query ($networkId: String!, $entityId: String!, $memberId: String!) {
    limitedToken(
      context: NETWORK, 
      networkId: $networkId, 
      entityId: $entityId, 
      impersonateMemberId: $memberId
    ) {
      accessToken
    }
  }
`;