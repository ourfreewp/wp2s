import * as coda from "@codahq/packs-sdk";

export const pack = coda.newPack();

const BaseUrl = "https://www.oddnews.com";

pack.setUserAuthentication({
	type: coda.AuthenticationType.WebBasic,
	uxConfig: {
		placeholderUsername: "Username",
		placeholderPassword: "Application Password",
	},
	getConnectionName: async function (context) {

		let url = coda.withQueryParams(`${BaseUrl}/wp-json/wp/v2/users/me`, {
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

pack.addNetworkDomain("oddnews.com");

function handleError(error) {
	if (error.statusCode < 200 || error.statusCode >= 300) {
		let message = error.body?.message ?? "Error";
		throw new coda.UserVisibleError(message);
	}
	throw error;
}

const StatusSchema = coda.makeObjectSchema({
	properties: {
		StatusId: {
			type: coda.ValueType.Number,
			description: "The unique id assigned to the status assigned by the system",
			fromKey: "id",
		},
		Name: {
			type: coda.ValueType.String,
			description: "The name of the status",
			fromKey: "name",
		},
		Slug: {
			type: coda.ValueType.String,
			description: "The slug of the status",
			fromKey: "slug",
		},
	},
	displayProperty: "Name",
	idProperty: "StatusId",
});

const PrioritySchema = coda.makeObjectSchema({
	properties: {
		PriorityId: {
			type: coda.ValueType.Number,
			description: "The unique id assigned to the priority assigned by the system",
			fromKey: "id",
		},
		Name: {
			type: coda.ValueType.String,
			description: "The name of the priority",
			fromKey: "name",
		},
		Slug: {
			type: coda.ValueType.String,
			description: "The slug of the priority",
			fromKey: "slug",
		},
	},
	displayProperty: "Name",
	idProperty: "PriorityId",
});

const TypeSchema = coda.makeObjectSchema({
	properties: {
		TypeId: {
			type: coda.ValueType.Number,
			description: "The unique id assigned to the type assigned by the system",
			fromKey: "id",
		},
		Name: {
			type: coda.ValueType.String,
			description: "The name of the type",
			fromKey: "name",
		},
		Slug: {
			type: coda.ValueType.String,
			description: "The slug of the type",
			fromKey: "slug",
		},
	},
	displayProperty: "Name",
	idProperty: "TypeId",
});

const ReporterSchema = coda.makeObjectSchema({
	properties: {
		ReporterId: {
			type: coda.ValueType.String,
			description: "The unique id assigned to the reporter assigned by the system",
			fromKey: "id",
		},
		Name: {
			type: coda.ValueType.String,
			description: "The name of the reporter",
			fromKey: "name",
		},
		Email: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Email,
			description: "The email of the reporter",
			fromKey: "email",
		},
	},
	displayProperty: "Name",
	idProperty: "ReporterId",
});

const ContextSchema = coda.makeObjectSchema({
	properties: {
		ContextString: {
			type: coda.ValueType.String,
			description: "",
			fromKey: "contextString",
		},
		ScreenSize: {
			type: coda.ValueType.Object,
			properties: {
				Width: {
					type: coda.ValueType.Number,
					description: "",
					fromKey: "width",
				},
				Height: {
					type: coda.ValueType.Number,
					description: "",
					fromKey: "height",
				},
				PixelRatio: {
					type: coda.ValueType.Number,
					description: "",
					fromKey: "pixelRatio",
				},
			},
			description: "",
			fromKey: "screenSize",
		},
		OperatingSystem: {
			type: coda.ValueType.Object,
			properties: {
				Family: {
					type: coda.ValueType.String,
					description: "",
					fromKey: "family",
				},
				Version: {
					type: coda.ValueType.String,
					description: "",
					fromKey: "version",
				},
			},
			description: "",
			fromKey: "operatingSystem",
		},
		Browser: {
			type: coda.ValueType.Object,
			properties: {
				Name: {
					type: coda.ValueType.String,
					description: "",
					fromKey: "name",
				},
				Version: {
					type: coda.ValueType.String,
					description: "",
					fromKey: "version",
				},
				UserAgent: {
					type: coda.ValueType.String,
					description: "",
					fromKey: "userAgent",
				},
			},
			description: "",
			fromKey: "browser",
		},
		Viewport:{
			type: coda.ValueType.Object,
			properties: {
				Width: {
					type: coda.ValueType.Number,
					description: "",
					fromKey: "width",
				},
				Height: {
					type: coda.ValueType.Number,
					description: "",
					fromKey: "height",
				},
			},
			description: "",
			fromKey: "viewport",
		},
		Zoom: {
			type: coda.ValueType.Object,
			properties: {
				Zoom: {
					type: coda.ValueType.Number,
					description: "",
					fromKey: "zoomFactor",
				},
			},
			description: "",
			fromKey: "zoom",
		},
		BrowserStack: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Url,
			description: "",
			fromKey: "browserStack",
		},
	},
	displayProperty: "ContextString",
});

const WebsiteSchema = coda.makeObjectSchema({
	properties: {
		Url: {
			type: coda.ValueType.String,
			description: "The URL of the context",
			fromKey: "url",
		},
		PageTitle: {
			type: coda.ValueType.String,
			description: "The page title of the context",
			fromKey: "pageTitle",
		},
		Domain: {
			type: coda.ValueType.String,
			description: "The domain of the context",
			fromKey: "domain",
		},
	},
	displayProperty: "PageTitle",
});

const IssueSchema = coda.makeObjectSchema({
	properties: {
		IssueId: {
			type: coda.ValueType.String,
			description: "The unique id assigned to the issue assigned by the system",
			fromKey: "id",
			displayName: "Id",
		},
		Title: {
			type: coda.ValueType.String,
			description: "The title of the issue. The title is initially set by the reporting user.",
			fromKey: "title",
			mutable: true,
		},
		Summary: {
			type: coda.ValueType.String,
			description: "The summary of the issue",
			fromKey: "excerpt",
			mutable: true,
		},
		Details:{
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Html,
			description: "The details of the issue",
			fromKey: "description",
			mutable: true,
		},
		CreatedAt: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.DateTime,
			description: "The date the issue was created in GMT",
			fromKey: "created_at",
			displayName: "Created At (GMT)",
		},
		UpdatedAt: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.DateTime,
			description: "The date the issue was last modified",
			fromKey: "updated_at",
		},
		Status: {
			...StatusSchema,
			description: "The status of the issue",
			fromKey: "status",
		},
		Priority: {
			...PrioritySchema,
			description: "The priority of the issue",
			fromKey: "priority",
		},
		Type: {
			...TypeSchema,
			description: "The type of the issue",
			fromKey: "type",
		},
		Screenshot: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.ImageReference,
			description: "The screenshot URL of the issue",
			fromKey: "screenshot_url",
		},
		ExternalLink: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Url,
			description: "The public URL of the issue",
			fromKey: "external_url",
			displayName: "External Link",
		},
		Meta: {
			type: coda.ValueType.String,
			description: "The custom data of the issue",
			fromKey: "custom_data",
			displayName: "Metadata",
		},
		Reporter: {
			...ReporterSchema,
			description: "The reporter of the issue",
			fromKey: "reporter",
		},
		Website: {
			...WebsiteSchema,
			description: "The website of the issue",
			fromKey: "website",
		},
		Context: {
			...ContextSchema,
			description: "The context of the issue",
			fromKey: "context",
		},
		Attachments: {
			type: coda.ValueType.String,
			description: "The attachments of the issue",
			fromKey: "attachments",
		},
	},
	displayProperty: "Title",
	idProperty: "IssueId",
	featuredProperties: ["Title", "Summary", "Screenshot"],
});

const IssueParameter = coda.makeParameter({
	type: coda.ParameterType.String,
	name: "Issue",
	description: "The ID of the issue.",
});

// Format Issues
function format_issues(raw_issues) {

	let formatted_issues = [];

	for (let raw_issue of raw_issues) {

		let issue_payload = raw_issue.reported_issue;

		let issue_data = issue_payload?.data;

		let issue = issue_data?.issue;

		let formatted_issue = {
			id: raw_issue.id,
			title: raw_issue.title?.raw,
			excerpt: raw_issue.excerpt?.raw,
			created_at: raw_issue.date_gmt,
			updated_at: raw_issue.modified_gmt,
			screenshot_url: issue.screenshotUrl,
			external_url: issue.publicUrl,
			description: issue?.description,
			console: issue?.console,
			network: issue?.network,
			custom_data: issue?.customData,
			reporter: issue_data?.reporter,
			website: issue_data?.website,
			context: issue_data?.context,
			destination: issue_data?.destination,
			attachments: issue_data?.attachments,
			status: issue_payload.status,
			priority: issue_payload?.priority,
			type: issue_payload?.type,
		};

		formatted_issues.push(formatted_issue);
	}

	return formatted_issues;
}

// Issues
pack.addSyncTable({
	name: "Issues",
	description: "Returns all issues.",
	identityName: "Issue",
	schema: IssueSchema,
	formula: {
		name: "Issues",
		description: "Syncs all reported issues.",
		parameters: [],
		execute: async function (args, context) {

			let page = context.sync.continuation?.page as number || 1;

			let requestQueries = {
				"context": "edit",
				"page": page,
				"per_page": 10,
				"order": "desc",
				"orderby": "date",
				"status": "any",
			};

			let url = coda.withQueryParams(`${BaseUrl}/wp-json/wp/v2/reported-issues`, requestQueries);

			let response = await context.fetcher.fetch({
				method: "GET",
				url: url,
			});

			let raw_issues = response.body;

			let formatted_issues = format_issues(raw_issues);

			let headers = response.headers;

			let totalPages = Number(headers["x-wp-totalpages"]);

			let continuation;

			if (totalPages > page) {
				continuation = {
					"page": page + 1,
				}
			}

			return {
				result: formatted_issues,
				continuation: continuation,
			};
		},
		maxUpdateBatchSize: 1,
		executeUpdate: async function (args, updates, context) {

			let update = updates[0];

			let changedIssue = update.newValue;

			let changedIssueId = changedIssue.id;

			let changedIssueFields = update.updatedFields;

			let changedPayload = {};

			for (let field of changedIssueFields) {
				changedPayload[field] = changedIssue[field];
			}

			let allowedFields = {
				"title": "title",
				"summary": "excerpt",
			};

			let filteredPayload = {};

			for (let field in changedPayload) {
				if (field in allowedFields) {
					filteredPayload[allowedFields[field]] = changedPayload[field];
				}
			}

			changedPayload = filteredPayload;

			let url = `${BaseUrl}/wp-json/wp/v2/reported-issues/${changedIssueId}`;

			let response = await context.fetcher.fetch({
				method: "POST",
				url: url,
				headers: {
					"Content-Type": "application/json"
				},
				body: JSON.stringify(changedPayload)
			});

			let updatedIssue = response.body;

			let updateResult = format_issues([updatedIssue]);

			return {
				result: updateResult,
			}
		},
	},
});

// Delete Issue
pack.addFormula({
	name: "DeleteIssue",
	description: "Delete an issue.",
	parameters: [
		IssueParameter
	],
	isAction: true,
	onError: handleError,
	resultType: coda.ValueType.Number,
	execute: async function ([Issue], context) {
		let url = `${BaseUrl}/wp-json/wp/v2/reported-issues/${Issue}`;
		let response = await context.fetcher.fetch({
			method: "DELETE",
			url: url,
		});
		let id = await response.body.id;
		return id;
	}
});
