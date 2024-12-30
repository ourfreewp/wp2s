import * as coda from "@codahq/packs-sdk";

export const pack = coda.newPack();

pack.setUserAuthentication({
	type: coda.AuthenticationType.WebBasic,
	uxConfig: {
		placeholderUsername: "Username",
		placeholderPassword: "Application Password",
	},
	requiresEndpointUrl: true,
	getConnectionName: async function (context) {

		let url = coda.withQueryParams(`${context.endpoint}/wp-json/wp/v2/users/me`, {
			"context": "edit"
		});

		let response = await context.fetcher.fetch({
			method: "POST",
			url: url
		});

		let body = await response.body;

		let username = encodeURIComponent(String(body.username));

		return `${username}`;
	},
});

function handleError(error) {
	if (error.statusCode < 200 || error.statusCode >= 300) {
		let message = error.body?.message ?? "Error";
		throw new coda.UserVisibleError(message);
	}
	throw error;
}

// Get Redirects

// Update Redirect

// Create Redirect

// Get Groups

const GroupSchema = coda.makeObjectSchema({
	properties: {
		GroupId: {
			type: coda.ValueType.String,
			description: "The ID of the group.",
			fromKey: "id",
			displayName: "Id",
		},
		Name: {
			type: coda.ValueType.String,
			description: "The name of the group.",
			fromKey: "name",
			displayName: "Name",
		},
		ModuleName: {
			type: coda.ValueType.String,
			description: "The name of the module.",
			fromKey: "moduleName",
			displayName: "Module",
		},
		ModuleId: {
			type: coda.ValueType.String,
			description: "The ID of the module.",
			fromKey: "moduleId",
			displayName: "Module Id",
		},
		Enabled: {
			type: coda.ValueType.Boolean,
			description: "Whether the group is enabled.",
			fromKey: "enabled",
			displayName: "Status",
		},
	},
	displayProperty: "Name",
	idProperty: "GroupId",
});

pack.addSyncTable({
	name: "Groups",
	description: "Returns all redirect groups.",
	identityName: "Group",
	schema: GroupSchema,
	formula: {
		name: "SyncGroups",
		description: "Syncs all redirect groups.",
		parameters: [],
		execute: async function (args, context) {

			let page = context.sync.continuation?.page as number || 0;

			let batch_size = 200;

			let requestQueries = {
				"page": page,
				"per_page": batch_size,
			};

			let url = coda.withQueryParams(`${context.endpoint}/wp-json/redirection/v1/group`, requestQueries);

			let response = await context.fetcher.fetch({
				method: "GET",
				url: url,
			});

			let items = response.body.items;

			let total = response.body.total;

			let page_count = Math.ceil(total / batch_size);

			let continuation;

			if (page < page_count) {
				continuation = {
					"page": page + 1,
				}
			}

			return {
				result: items,
				continuation: continuation,
			};
		},
	},
});

const RedirectSchema = coda.makeObjectSchema({
	properties: {
		RedirectId: {
			type: coda.ValueType.String,
			description: "The ID of the redirect.",
			fromKey: "id",
			displayName: "Id",
		},
		Title: {
			type: coda.ValueType.String,
			description: "A descriptive title for the redirect, or empty for no title.",
			fromKey: "title",
			displayName: "Title",
		},
		SourceUrl: {
			type: coda.ValueType.String,
			description: "Source URL to match.",
			fromKey: "url",
			displayName: "Source URL",
		},
		MatchUrl: {
			type: coda.ValueType.String,
			description: "Match URL.",
			fromKey: "match_url",
			displayName: "Match URL",
		},
		MatchData: {
			type: coda.ValueType.String,
			description: "Match against the source.",
			fromKey: "match_data",
			displayName: "Match Data",
		},
		MatchType: {
			type: coda.ValueType.String,
			description: "What URL matching to use.",
			fromKey: "match_type",
			displayName: "Match Type",
		},
		ActionType: {
			type: coda.ValueType.String,
			description: "What to do when the URL is matched.",
			fromKey: "action_type",
			displayName: "Action",
		},
		ActionCode: {
			type: coda.ValueType.String,
			description: "The HTTP code to return.",
			fromKey: "action_code",
			displayName: "HTTP Code",
		},
		ActionData: {
			type: coda.ValueType.String,
			description: "Any data associated with the action_type. For example, the target URL.",
			fromKey: "action_data",
			displayName: "Action Data",
		},
		Hits: {
			type: coda.ValueType.Number,
			description: "Number of hits this redirect has received.",
			fromKey: "hits",
			displayName: "Hits",
		},
		Regex: {
			type: coda.ValueType.Boolean,
			description: "True for regular expression, false otherwise.",
			fromKey: "regex",
			displayName: "Regex",
		},
		GroupId: {
			type: coda.ValueType.String,
			description: "The group this redirect belongs to.",
			fromKey: "group_id",
			displayName: "Group Id",
		},
		Position: {
			type: coda.ValueType.Number,
			description: "Redirect position, used to determine order multiple redirects occur.",
			fromKey: "position",
			displayName: "Position",
		},
		LastAccess: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.DateTime,
			description: "The date this redirect was last hit.",
			fromKey: "last_access",
			displayName: "Last Access",
		},
		Status: {
			type: coda.ValueType.Boolean,
			codaType: coda.ValueHintType.Toggle,
			description: "Status of the redirect.",
			fromKey: "enabled",
			displayName: "Status",
		},
	},
	displayProperty: "SourceUrl",
	idProperty: "RedirectId",
	featuredProperties: ["Title", "SourceUrl", "ActionType", "ActionCode", "ActionData", "Hits", "LastAccess", "Status"],
});


pack.addSyncTable({
	name: "Redirects",
	description: "Returns all redirects.",
	identityName: "Redirects",
	schema: RedirectSchema,
	formula: {
		name: "SyncRedirects",
		description: "Syncs all redirects.",
		parameters: [],
		execute: async function (args, context) {

			let page = context.sync.continuation?.page as number || 0;

			let batch_size = 200;

			let requestQueries = {
				"page": page,
				"per_page": batch_size,
			};

			let url = coda.withQueryParams(`${context.endpoint}/wp-json/redirection/v1/redirect`, requestQueries);

			let response = await context.fetcher.fetch({
				method: "GET",
				url: url,
			});

			let items = response.body.items;

			for (let item of items) {
				if (item.last_access && !Date.parse(item.last_access)) {
					item.last_access = null;
				}
			}

			let total = response.body.total;

			let page_count = Math.ceil(total / batch_size);

			let continuation;

			if (page < page_count) {
				continuation = {
					"page": page + 1,
				}
			}

			return {
				result: items,
				continuation: continuation,
			};
		},
	},
});

pack.addFormula({
	name: "CreateRedirect",
	description: "Create a new redirect.",
	parameters: [
		// Source URL
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "SourceURL",
			description: "The relative URL you want to redirect from",
		}),
		// Query Parameters
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "QueryParameters",
			description: "Ignore query parameters.",
			suggestedValue: "exact",
			autocomplete: [
				{
					display: "Exact match in any order",
					value: "exact",
				},
				{
					display: "Ignore all parameters",
					value: "ignore",
				},
				{
					display: "Ignore & pass parameters to the target",
					value: "pass",
				},
			]
		}),
		// Target URL
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "TargetURL",
			description: "The target URL you want to redirect, or auto-complete on post name or permalink.",
		}),
		// Group
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "Group",
			description: "The group this redirect belongs to.",
		}),
		// Regex
		coda.makeParameter({
			type: coda.ParameterType.Boolean,
			name: "Regex",
			description: "True for regular expression, false otherwise.",
			suggestedValue: false,
			optional: true,
		}),
		// Ignore Case
		coda.makeParameter({
			type: coda.ParameterType.Boolean,
			name: "IgnoreCase",
			description: "Ignore case.",
			suggestedValue: true,
			optional: true,
		}),
		// Ignore Slash
		coda.makeParameter({
			type: coda.ParameterType.Boolean,
			name: "IgnoreSlash",
			description: "Ignore trailing slash.",
			suggestedValue: true,
			optional: true,
		}),
		// Title
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "Title",
			description: "A descriptive title for the redirect, or empty for no title.",
			optional: true,
		}),
		// MatchType
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "MatchType",
			description: "What URL matching to use.",
			suggestedValue: "url",
			autocomplete: [
				{
					display: "URL only",
					value: "url",
				},
			],
			optional: true,
		}),
		// ActionType
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "ActionType",
			description: "What to do when the URL is matched.",
			suggestedValue: "url",
			autocomplete: [
				{
					display: "Redirect to URL",
					value: "url",
				},
				{
					display: "Redirect to random post",
					value: "random",
				},
				{
					display: "Pass-through",
					value: "pass",
				},
				{
					display: "Error (404)",
					value: "error",
				},
				{
					display: "Do nothing (ignore)",
					value: "nothing",
				},
			],
			optional: true,
		}),
		// ActionCode
		coda.makeParameter({
			type: coda.ParameterType.String,
			name: "ActionCode",
			description: "The HTTP code to return.",
			suggestedValue: "301",
			autocomplete: [
				{
					display: "301 - Moved Permanently",
					value: "301",
				},
				{
					display: "302 - Found",
					value: "302",
				},
				{
					display: "303 - See Other",
					value: "303",
				},
				{
					display: "304 - Not Modified",
					value: "304",
				},
				{
					display: "307 - Temporary Redirect",
					value: "307",
				},
				{
					display: "308 - Permanent Redirect",
					value: "308",
				},
			],
			optional: true,
		}),
		// Position
		coda.makeParameter({
			type: coda.ParameterType.Number,
			name: "Position",
			description: "Redirect position, used to determine order multiple redirects occur.",
			optional: true,
		}),
		// ExcludeFromLogs
		coda.makeParameter({
			type: coda.ParameterType.Boolean,
			codaType: coda.ValueHintType.Toggle,
			name: "ExcludeFromLogs",
			description: "Exclude this redirect from logs.",
			suggestedValue: false,
			optional: true,
		}),
	],
	isAction: true,
	onError: handleError,
	resultType: coda.ValueType.Number,
	execute: async function ([
		SourceURL,
		QueryParameters,
		TargetURL,
		Group,
		Regex,
		IgnoreCase,
		IgnoreSlash,
		Title,
		MatchType,
		ActionType,
		ActionCode,
		Position,
		ExcludeFromLogs
	], context) {

		let requestQueries = {
			"orderby": "id",
			"direction": "desc",
			"per_page": 25,
			"v": context.invocationToken,
		};

		let url = coda.withQueryParams(`${context.endpoint}/wp-json/redirection/v1/redirect`, requestQueries);

		let body = {
			"id": 0,
			"url": SourceURL,
			"title": Title || "",
			"match_data": {
				"source": {
					"flag_regex": Regex || false,
					"flag_trailing": IgnoreSlash || true,
					"flag_case": IgnoreCase || true,
					"flag_query": QueryParameters || "exact",
				},
				"options": {
					"log_exclude": ExcludeFromLogs || false,
				}
			},
			"match_type": MatchType || "url",
			"action_type": ActionType || "url",
			"position": Position || 0,
			"group_id": Group,
			"action_code": ActionCode || "301",
			"action_data": {
				"url": TargetURL,
			}
		};

		let response = await context.fetcher.fetch({
			method: "POST",
			url: url,
			headers: {
				"Content-Type": "application/json",
				"Accept": "application/json",
			},
			body: JSON.stringify(body),
		});
		let status = await response.status;
		return status;
	}
});