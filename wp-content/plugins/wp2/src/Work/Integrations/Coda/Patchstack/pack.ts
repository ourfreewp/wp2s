import * as coda from "@codahq/packs-sdk";

export const pack = coda.newPack();

pack.addNetworkDomain('patchstack.com');

const BaseUrl = 'https://api.patchstack.com/monitor';

pack.setUserAuthentication({
	type: coda.AuthenticationType.CustomHeaderToken,
	headerName: "UserToken",
});

const KeyValueSchema = coda.makeObjectSchema({
	properties: {
		RecordId: {
			type: coda.ValueType.String,
			description: "The unique identifier of the record.",
			fromKey: "id",
		},
		Label: {
			type: coda.ValueType.String,
			description: "The key of the key-value pair.",
			fromKey: "key",
		},
		Value: {
			type: coda.ValueType.String,
			description: "The value of the key-value pair.",
			fromKey: "value",
		},
	},
	displayProperty: "Label",
	idProperty: "RecordId",
});

const ModuleSchema = coda.makeObjectSchema({
	properties: {
		ModuleId: {
			type: coda.ValueType.String,
			description: "The unique identifier of the module.",
			fromKey: "id",
		},
		Title: {
			type: coda.ValueType.String,
			description: "The name of the module.",
			fromKey: "name",
		},
	},
	idProperty: "ModuleId",
	displayProperty: "Title",
});

const SiteGroupSchema = coda.makeObjectSchema({
	properties: {
		GroupId: {
			type: coda.ValueType.String,
			description: "The unique identifier of the group.",
			fromKey: "id",
		},
		Title: {
			type: coda.ValueType.String,
			description: "The name of the group.",
			fromKey: "title",
		},
		Modules: {
			type: coda.ValueType.Array,
			items: ModuleSchema,
			description: "The modules of the group.",
			fromKey: "modules",
		},
	},
	displayProperty: "Title",
	idProperty: "GroupId",
});

const SiteSchema = coda.makeObjectSchema({
	properties: {
		SiteId: {
			type: coda.ValueType.String,
			description: "The unique identifier of the site.",
			fromKey: "id",
		},
		Url: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Url,
			description: "The URL of the site.",
			fromKey: "url",
		},
		Domain: {
			type: coda.ValueType.String,
			description: "The domain of the site.",
			fromKey: "url_domain",
		},
		DistId: {
			type: coda.ValueType.String,
			description: "The distribution identifier of the site.",
			fromKey: "dist_id",
		},
		UpdatedAt: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.DateTime,
			description: "The update date of the site.",
			fromKey: "updated_at",
		},
		CMS: {
			type: coda.ValueType.String,
			description: "The CMS of the site.",
			fromKey: "cms",
		},
		VulnerablePlugins: {
			type: coda.ValueType.Boolean,
			codaType: coda.ValueHintType.Toggle,
			description: "Whether the site has a vulnerable plugin.",
			fromKey: "has_vuln_plugin",
		},
		Type: {
			type: coda.ValueType.String,
			description: "The type of the site.",
			fromKey: "type",
		},
		FirewallEnabled: {
			type: coda.ValueType.Boolean,
			codaType: coda.ValueHintType.Toggle,
			description: "Whether the firewall is enabled on the site.",
			fromKey: "firewall_enabled",
		},
		PhpFirewallInstalled: {
			type: coda.ValueType.Boolean,
			codaType: coda.ValueHintType.Toggle,
			description: "Whether the PHP firewall is installed on the site.",
			fromKey: "is_php_firewall_installed",
		},
		OwnerType: {
			type: coda.ValueType.String,
			description: "The owner type of the site.",
			fromKey: "owner_type",
		},
		AddonProtection: {
			type: coda.ValueType.Boolean,
			codaType: coda.ValueHintType.Toggle,
			description: "Whether the site has addon protection.",
			fromKey: "addon_protection",
		},
		Class: {
			type: coda.ValueType.String,
			description: "The class of the site",
			fromKey: "class",
		},
		PluginInstalled: {
			type: coda.ValueType.Boolean,
			codaType: coda.ValueHintType.Toggle,
			description: "Whether the plugin is installed on the site.",
			fromKey: "plugin_installed",
		},
		ComponentsCount: {
			type: coda.ValueType.Number,
			description: "The number of components on the site.",
			fromKey: "components_count",
		},
		ComponentsOutdated: {
			type: coda.ValueType.Number,
			description: "The number of outdated components on the site.",
			fromKey: "components_outdated",
		},
		VulnerableTotal: {
			type: coda.ValueType.Number,
			description: "The total number of vulnerabilities on the site.",
			fromKey: "vulnerable_total",
		},
		FirewallActivities: {
			type: coda.ValueType.Array,
			items: KeyValueSchema,
			description: "The firewall activity of the site.",
			fromKey: "activity_firewall",
		},
		PluginVersion: {
			type: coda.ValueType.String,
			description: "The plugin version of the site.",
			fromKey: "plugin_version",
		},
		ChartDataset: {
			type: coda.ValueType.Array,
			items: KeyValueSchema,
			description: "The chart data of the site.",
			fromKey: "chart_data",
		},
		Modules: {
			type: coda.ValueType.Array,
			items: ModuleSchema,
			description: "The modules of the site.",
			fromKey: "modules",
		},
		Groups: {
			type: coda.ValueType.Array,
			items: SiteGroupSchema,
			description: "The groups of the site.",
			fromKey: "site_groups",
		},
	},
	displayProperty: "Domain",
	idProperty: "SiteId",
});

function process_site_key_values(prefix, pairs) {
	let formatted_data = [];

	for (let key in pairs) {

		let formatted_key = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

		let formatted_pair = {
			id: `${prefix}_${key}`,
			date: formatted_key,
			value: pairs[key],
		};
		formatted_data.push(formatted_pair);
	}

	return formatted_data;
}

function format_site_ping(raw_site_ping) {
	let formatted_site_ping = {
		id: raw_site_ping.id,
		site_id: raw_site_ping.site_id,
		firewall_status: raw_site_ping.firewall_status === 1,
		created_at: raw_site_ping.created_at,
		updated_at: raw_site_ping.updated_at,
		is_old: raw_site_ping.is_old === 1,
		site_url: raw_site_ping.site_url,
		sent_pings: raw_site_ping.sent_pings,
		deactivated: raw_site_ping.deactivated,
		minutes: raw_site_ping.minutes,
	};

	return formatted_site_ping;
}

function process_site_chart_data(site_id, raw_chart_data) {
	let formatted_chart_data = [];

	for (let raw_chart_data_item of raw_chart_data) {
		let formatted_chart_data_item = {
			id: `${site_id}_${raw_chart_data_item.id}`,
			date: raw_chart_data_item.date,
			value: raw_chart_data_item.value,
		};
		formatted_chart_data.push(formatted_chart_data_item);
	}

	return formatted_chart_data;
}

function format_sites(raw_sites) {
	let formatted_sites = [];

	for (let raw_site of raw_sites) {
		let formatted_site = {
			id: raw_site.id,
			url: raw_site.url,
			url_domain: raw_site.url_domain,
			dist_id: raw_site.dist_id,
			updated_at: raw_site.updated_at,
			cms: raw_site.cms,
			has_vuln_plugin: raw_site.has_vuln_plugin === 1,
			type: raw_site.type,
			firewall_enabled: raw_site.firewall_enabled === 1,
			is_php_firewall_installed: raw_site.is_php_firewall_installed === 1,
			owner_type: raw_site.owner_type,
			addon_protection: raw_site.addon_protection === 1,
			class: raw_site.class,
			ping: format_site_ping(raw_site.ping) || null,
			plugin_installed: raw_site.plugin_installed === 1,
			plugin_version: raw_site.plugin_version,
			components_count: raw_site.counters.components_count,
			components_outdated: raw_site.counters.components_outdated,
			vulnerable_total: raw_site.counters.vulnerable_total,
			activity_firewall: process_site_key_values(raw_site.ping.id, raw_site.counters.activity_firewall) || null,
			chart_data: process_site_chart_data(raw_site.id, raw_site.chart_data) || null,
			modules: raw_site.modules || null,
			site_groups: raw_site.site_groups,
		};
		formatted_sites.push(formatted_site);
	}

	return formatted_sites;
}

const SortColumnParameter = coda.makeParameter({
	type: coda.ParameterType.String,
	name: "SortColumn",
	description: "The column to sort by.",
	suggestedValue: "url",
	autocomplete: [
		{
			"display": "Id",
			"value": "id",
		},
		{
			"display": "Connected",
			"value": "connected",
		},
		{
			"display": "Protected",
			"value": "protected",
		},
		{
			"display": "Software",
			"value": "software",
		},
		{
			"display": "Vulnerable",
			"value": "vulnerable",
		},
		{
			"display": "Outdated",
			"value": "outdated",
		},
		{
			"display": "Url",
			"value": "url",
		},
	],
});

const SortDirectionParameter = coda.makeParameter({
	type: coda.ParameterType.String,
	name: "SortDirection",
	description: "The direction to sort by.",
	suggestedValue: "asc",
	autocomplete: [
		{
			"display": "Ascending",
			"value": "asc",
		},
		{
			"display": "Descending",
			"value": "desc",
		},
	],
});

const SiteSearchParameter = coda.makeParameter({
	type: coda.ParameterType.String,
	name: "Search",
	description: "The search term to filter sites by.",
	optional: true,
});

pack.addSyncTable({
	name: "Sites",
	description: "List of sites.",
	identityName: "Site",
	schema: SiteSchema,
	formula: {
		name: "SyncSites",
		description: "Syncs sites.",
		parameters: [
			SortColumnParameter,
			SortDirectionParameter,
			SiteSearchParameter,
		],
		execute: async function ([SortColumn,SortDirection,Search], context) {

			let previousContinuation = context.sync.continuation;

			let page;

			let initialPage = 1;

			if (previousContinuation) {
				page = previousContinuation.page;
			} else {
				page = initialPage;
			}

			let url = coda.withQueryParams(`${BaseUrl}/sites/list`, {
				search: Search || "",
				column: SortColumn || "url",
				direction: SortDirection || "asc",
				per_page: 50,
				page: page || initialPage,
			});
			let response = await context.fetcher.fetch({
				method: "POST",
				url: url,
				headers: {
					"Accept": "application/json",
					"Content-Type": "application/json",
				},
			});
			let raw_sites = response.body.sites;

			let nextContinuation = undefined;

			let moreItemsLeft = raw_sites.length === 50;

			if (moreItemsLeft) {
				nextContinuation = {
					page: page + 1,
				};
			}

			let formatted_sites = format_sites(raw_sites);

			return {
				result: formatted_sites,
				continuation: nextContinuation,
			};
		},
	},
});

function extract_site_groups(raw_sites) {
	let site_groups = [];

	for (let raw_site of raw_sites) {
		let raw_site_groups = raw_site.site_groups;
		for (let raw_site_group of raw_site_groups) {
			let site_group = {
				id: raw_site_group.id,
				title: raw_site_group.title,
				modules: raw_site_group.modules,
			};
			site_groups.push(site_group);
		}
	}

	return site_groups;
}

pack.addSyncTable({
	name: "SiteGroups",
	description: "List of site groups.",
	identityName: "SiteGroup",
	schema: SiteGroupSchema,
	formula: {
		name: "SyncSiteGroups",
		description: "Syncs site groups.",
		parameters: [],
		execute: async function (args, context) {

			let previousContinuation = context.sync.continuation;

			let page;

			let initialPage = 1;

			if (previousContinuation) {
				page = previousContinuation.page;
			} else {
				page = initialPage;
			}

			let url = coda.withQueryParams(`${BaseUrl}/sites/list`, {
				search: "",
				column: "url",
				direction: "asc",
				per_page: 50,
				page: page || initialPage,
			});
			let response = await context.fetcher.fetch({
				method: "POST",
				url: url,
				headers: {
					"Accept": "application/json",
					"Content-Type": "application/json",
				},
			});
			let raw_sites = response.body.sites;

			let nextContinuation = undefined;

			let moreItemsLeft = raw_sites.length === 50;

			if (moreItemsLeft) {
				nextContinuation = {
					page: page + 1,
				};
			}

			let site_groups = extract_site_groups(raw_sites);

			return {
				result: site_groups,
				continuation: nextContinuation,
			};
		},
	},
});