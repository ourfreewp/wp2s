import * as coda from "@codahq/packs-sdk";

export const pack = coda.newPack();

pack.setUserAuthentication({
    type: coda.AuthenticationType.WebBasic,
    uxConfig: {
        placeholderUsername: "Username",
        placeholderPassword: "Application Password",
    },
    requiresEndpointUrl: true,
    instructionsUrl: "https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/",
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

        return `${username} (${context.endpoint})`;
    },
});

function handleError(error) {
    if (error.statusCode < 200 || error.statusCode >= 300) {
        let message = error.body?.message ?? "Error";
        throw new coda.UserVisibleError(message);
    }
    throw error;
}

const UrlParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Url",
    description: "The URL of the page to scrape.",
});

const TypeParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Type",
    description: "The type of content to be created.",
    autocomplete: [
        {
            display: "Article",
            value: "article"
        },
        {
            display: "Slideshow",
            value: "slideshow"
        },
        {
            display: "Topic",
            value: "topic"
        },
        {
            display: "Tag",
            value: "tag"
        },
        {
            display: "Author",
            value: "author"
        }
    ]
});

const OptionalTitleParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Title",
    description: "The title of the content to be created.",
    optional: true,
});

const SnapshotSummarySchema = coda.makeObjectSchema({
    properties: {
        ContentId: {
            type: coda.ValueType.String,
            fromKey: 'ID'
        },
        TotalSnapshots: {
            type: coda.ValueType.Number,
            fromKey: 'snapshot_count'
        },
    },
    idProperty: 'ContentId',
    displayProperty: 'TotalSnapshots',
});

const ContentIdParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "ContentId",
    description: "The ID of the content to be created.",
});


const AuthorParameter = coda.makeParameter({
    type: coda.ParameterType.Number,
    name: "Author",
    description: "The author of the content to be created.",
});

const DateParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Date",
    description: "The date of the content to be created.",
});

const TitleParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Title",
    description: "The title of the content to be created.",
});

const ItemSchema = coda.makeObjectSchema({
    properties: {
        ContentId: {
            type: coda.ValueType.String,
            fromKey: 'ID'
        },
        Title: {
            type: coda.ValueType.String,
            fromKey: 'title'
        },
        Type: {
            type: coda.ValueType.String,
            fromKey: 'type'
        },
        Url: {
            type: coda.ValueType.String,
            fromKey: 'url'
        },
    },
    idProperty: 'ContentId',
    displayProperty: 'Title',
});

pack.addFormula({
    name: "SyncPost",
    description: "Syncs a post from a URL.",
    parameters: [
        UrlParameter,
        TypeParameter,
        TitleParameter,
        AuthorParameter,
        DateParameter,
    ],
    isAction: true,
    resultType: coda.ValueType.String,
    onError: handleError,
    execute: async function ([Url, Type, Title, Author, Date], context) {

        let apiUrl = `${context.endpoint}/wp-json/coda-pack/v26553/sync`;

        let timezone = context.timezone;

        let response = await context.fetcher.fetch({
            method: "POST",
            url: apiUrl,
            body: JSON.stringify({
                "url": Url,
                "type": Type,
                "title": Title,
                "author": Author,
                "date": Date,
                "timezone": timezone
            }),
            headers: {
                "Content-Type": "application/json",
            },
        });

        let body = await response.body;

        return body;

    },
});

pack.addFormula({
    name: "Item",
    description: "Returns the item from a given URL.",
    parameters: [
        UrlParameter,
        TypeParameter,
    ],
    resultType: coda.ValueType.Object,
    schema: ItemSchema,
    onError: handleError,
    cacheTtlSecs: 5,
    execute: async function ([Url, Type], context) {

        let apiUrl = `${context.endpoint}/wp-json/coda-pack/v26553/item`;

        apiUrl = coda.withQueryParams(apiUrl, {
            "type": Type,
            "url": Url
        });

        let response = await context.fetcher.fetch({
            method: "GET",
            url: apiUrl,
            cacheTtlSecs: 5,
            headers: {
                "Content-Type": "application/json",
            },
        });

        let body = await response.body;

        return body;

    },
});

pack.addFormula({
    name: "SnapshotSummary",
    description: "Returns a summary of the snapshots for a given content.",
    parameters: [
        ContentIdParameter,
    ],
    resultType: coda.ValueType.Object,
    schema: SnapshotSummarySchema,
    onError: handleError,
    cacheTtlSecs: 5,
    execute: async function ([ContentId], context) {

        let apiUrl = `${context.endpoint}/wp-json/coda-pack/v26553/snapshot-summary`;

        apiUrl = coda.withQueryParams(apiUrl, {
            "content_id": ContentId
        });

        let response = await context.fetcher.fetch({
            method: "GET",
            url: apiUrl,
            cacheTtlSecs: 5,
            headers: {
                "Content-Type": "application/json",
            },
        });

        let body = await response.body;

        return body;

    },
});

pack.addFormula({
    name: "RequestSnapshot",
    description: "Requests a snapshot of a given URL.",
    parameters: [
        UrlParameter,
        TypeParameter,
        ContentIdParameter,
    ],
    isAction: true,
    resultType: coda.ValueType.String,
    onError: handleError,
    execute: async function ([Url, Type, ContentId], context) {

        let apiUrl = `${context.endpoint}/wp-json/coda-pack/v26553/snapshot`;

        let response = await context.fetcher.fetch({
            method: "POST",
            url: apiUrl,
            body: JSON.stringify({
                "url": Url,
                "type": Type,
                "content_id": ContentId,
            }),
            headers: {
                "Content-Type": "application/json",
            },
        });

        let body = await response.body;

        return body;

    },
});