export const INVITE_MEMBER_MUTATION = `
  mutation ($invitees: [InviteMemberInput!]!) {
    inviteMembers(input: { invitees: $invitees }) {
      id
      status
    }
  }
`;