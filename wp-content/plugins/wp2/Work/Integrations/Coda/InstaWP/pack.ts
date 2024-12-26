import * as coda from "@codahq/packs-sdk";

export const pack = coda.newPack();

pack.addNetworkDomain("instawp.io");

pack.setUserAuthentication({
	type: coda.AuthenticationType.HeaderBearerToken,
});

// const TeamParameter = coda.makeParameter({
// 	type: coda.ParameterType.String,
// 	name: "Team",
// 	description: "The team name.",
// 	autocomplete: async function (context, search) {
// 		let url = coda.withQueryParams(
// 			"https://app.instawp.io/api/v2/teams"
// 		);
// 		let response = await context.fetcher.fetch({
// 			method: "GET",
// 			url: url,
// 			headers: {
// 				"Content-Type": "application/json",
// 				"Accept": "application/json",
// 			}
// 		});
// 		console.log(response.body);
// 		let results = response.body.data;
// 		return coda.autocompleteSearchObjects(search, results, "name", "id");
// 	},
// });

const LiveSiteParameter = coda.makeParameter({
	type: coda.ParameterType.String,
	name: "LiveSite",
	description: "The URL of the live site.",
});

const StagingSiteParameter = coda.makeParameter({
	type: coda.ParameterType.String,
	name: "StagingSite",
	description: "The URL of the staging site.",
});

// Connect Site
pack.addFormula({
	name: "ConnectSite",
	description: "Connect a staging site (child site) to a production site (parent site) via InstaWP API.",
	parameters: [
		LiveSiteParameter,
		StagingSiteParameter,
	],
	resultType: coda.ValueType.String,
	isAction: true,
	execute: async function ([LiveSite, StagingSite], context) {

		let response = await context.fetcher.fetch({
			url: "https://app.instawp.io/api/v2/connects/link",
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				"parent_url": LiveSite,
				"child_url": StagingSite,
			}),
		});

		let message = response.body.message;
		console.log(message);
		return message;
	},
});

// Create token

pack.addFormula({
	name: "CreateSiteToken",
	description: "Create a token for a site via InstaWP API.",
	parameters: [
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "Url",
			description: "The URL of the site.",
		}),
	],
	resultType: coda.ValueType.String,
	isAction: true,
	execute: async function ([Url], context) {

		let response = await context.fetcher.fetch({
			url: "https://app.instawp.io/api/v2/connects/create-token",
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				"Accept": "application/json",
			},
			body: JSON.stringify({
				"url": Url,
			}),
		});

		let token = response.body;
		console.log(token);
		return token;
	},
});

// Sync Hosted Sites

const LiveSiteSchema = coda.makeObjectSchema({
	properties: {
		SiteId: {
			type: coda.ValueType.String,
			description: "The ID of the site.",
			fromKey: "id",
		},
		Url: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Url,
			description: "The URL of the site.",
			fromKey: "url",
		},
		CreatedAt: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.DateTime,
			description: "The date the site was created.",
			fromKey: "created_at",
		},
		Datacenter: {
			type: coda.ValueType.String,
			description: "The datacenter of the site.",
			fromKey: "datacenter",
		},
		CreatedDate: {
			type: coda.ValueType.String,
			description: "The date the site was created.",
			fromKey: "created_date",
		},
	},
	idProperty: "SiteId",
	displayProperty: "SiteId",
});

pack.addSyncTable({
	name: "LiveSites",
	description: "The live, production sites.",
	identityName: "LiveSite",
	schema: LiveSiteSchema,
	formula: {
		name: "SyncLiveSites",
		description: "Syncs the live sites from InstaWP.",
		parameters: [],
		execute: async function (args, context) {

			let requestUrl: string = (context.sync.continuation?.requestUrl as string) || "https://app.instawp.io/api/v2/hosting/live/info";

			let url = coda.withQueryParams(
				requestUrl,
				{
					page: 1,
					per_page: 15,
				}
			);
			let response = await context.fetcher.fetch({
				method: "GET",
				headers: {
					"Content-Type": "application/json",
					"Accept": "application/json",
				},
				url: url
			});
			let body = response.body;
			let sites = body.data.data;

			let continuation;

			let next_page_url = body.data.next_page_url;

			if (body.data.next_page_url) {
				continuation = {
					requestUrl: next_page_url,
				};
			}

			return {
				result: sites,
			};
		},
	},
});

// // Sync Staging Sites

const StagingSiteSchema = coda.makeObjectSchema({
	properties: {
		SiteId: {
			type: coda.ValueType.String,
			description: "The ID of the site.",
			fromKey: "id",
		},
		Name: {
			type: coda.ValueType.String,
			description: "The name of the site.",
			fromKey: "name",
		},
		Url: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Url,
			description: "The URL of the site.",
			fromKey: "url",
		},
	},
	idProperty: "SiteId",
	displayProperty: "SiteId",
});

pack.addSyncTable({
	name: "StagingSites",
	description: "The staging sites.",
	identityName: "StagingSite",
	schema: StagingSiteSchema,
	formula: {
		name: "SyncStagingSites",
		description: "Syncs the staging sites from InstaWP.",
		parameters: [],
		execute: async function (args, context) {

			let requestUrl: string = (context.sync.continuation?.requestUrl as string) || "https://app.instawp.io/api/v2/sites";

			let url = coda.withQueryParams(
				requestUrl,
				{ 
					page: 1, 
					per_page: 15 
				}
			);
			let response = await context.fetcher.fetch({
				method: "GET",
				headers: {
					"Content-Type": "application/json",
					"Accept": "application/json",
				},
				url: url
			});
			let body = response.body;
			console.log(body);
			let sites = body.data;

			let continuation;

			let next_page_url = body.data.next_page_url;

			if (body.data.next_page_url) {
				continuation = {
					requestUrl: next_page_url,
				};
			}

			return {
				result: sites,
				continuation: continuation,
			};
		},
	},
});

const WorkspaceSchema = coda.makeObjectSchema({
	properties: {
		WorkspaceId: {
			type: coda.ValueType.String,
			description: "The ID of the workspace.",
			fromKey: "id",
		},
		Name: {
			type: coda.ValueType.String,
			description: "The name of the workspace.",
			fromKey: "name",
		},
		Slug: {
			type: coda.ValueType.String,
			description: "The slug of the workspace.",
			fromKey: "store_slug",
		},
		Description: {
			type: coda.ValueType.String,
			description: "The description of the workspace.",
			fromKey: "store_description",
		},	
		Logo: {
			type: coda.ValueType.String,
			description: "The logo of the workspace.",
			fromKey: "store_logo",
		},
		CreatedAt: {
			type: coda.ValueType.String,
			description: "The date the workspace was created.",
			fromKey: "created_at",
		},
		UpdatedAt: {
			type: coda.ValueType.String,
			description: "The date the workspace was updated.",
			fromKey: "updated_at",
		},
	},
	idProperty: "WorkspaceId",
	displayProperty: "Name",
});

pack.addSyncTable({
	name: "Workspaces",
	description: "The workspaces.",
	identityName: "Workspace",
	schema: WorkspaceSchema,
	formula: {
		name: "SyncWorkspaces",
		description: "Syncs the workspaces.",
		parameters: [],
		execute: async function (args, context) {

			let url = coda.withQueryParams(
				"https://app.instawp.io/api/v2/teams"
			);

			let response = await context.fetcher.fetch({
				method: "GET",
				headers: {
					"Content-Type": "application/json",
					"Accept": "application/json",
				},
				url: url
			});

			let body = response.body;

			let teams = body.data;

			console.log(teams);

			return {
				result: teams,
			};
		},
	},
});