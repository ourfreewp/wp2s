import * as coda from "@codahq/packs-sdk";

export const pack = coda.newPack();

pack.setUserAuthentication({
    type: coda.AuthenticationType.HeaderBearerToken,
    instructionsUrl: "https://simplecirc.com/docs/api#authentication",

    getConnectionName: async function (context) {
        return "SimpleCirc";
    },
});

pack.addNetworkDomain("simplecirc.com");

const SubscriberSchema = coda.makeObjectSchema({
    properties: {
        AccountId: {
            type: coda.ValueType.String,
            description: "The unique identifier for the subscriber.",
            fromKey: "account_id",
        },
        RenewalLink: {
            type: coda.ValueType.String,
            description: "The link to renew the subscriber's subscription.",
            fromKey: "renewal_link",
        },
        LoginLink: {
            type: coda.ValueType.String,
            description: "The link to login to the subscriber's account.",
            fromKey: "login_link",
        },
        Name: {
            type: coda.ValueType.String,
            description: "The full name of the subscriber.",
            fromKey: "name",
        },
        FirstName: {
            type: coda.ValueType.String,
            description: "The first name of the subscriber.",
            fromKey: "first_name",
        },
        LastName: {
            type: coda.ValueType.String,
            description: "The last name of the subscriber.",
            fromKey: "last_name",
        },
        Email: {
            type: coda.ValueType.String,
            description: "The email address of the subscriber.",
            fromKey: "email",
        },
        Company: {
            type: coda.ValueType.String,
            description: "The company of the subscriber.",
            fromKey: "company",
        },
        Phone: {
            type: coda.ValueType.String,
            description: "The phone number of the subscriber.",
            fromKey: "phone",
        },
        Title: {
            type: coda.ValueType.String,
            description: "The title of the subscriber.",
            fromKey: "title",
        },
        Address: {
            type: coda.ValueType.Object,
            properties: {
                Address1: {
                    type: coda.ValueType.String,
                    description: "The first line of the subscriber's address.",
                    fromKey: "address.address_1",
                },
                Address2: {
                    type: coda.ValueType.String,
                    description: "The second line of the subscriber's address.",
                    fromKey: "address.address_2",
                },
                City: {
                    type: coda.ValueType.String,
                    description: "The city of the subscriber's address.",
                    fromKey: "address.city",
                },
                State: {
                    type: coda.ValueType.String,
                    description: "The state of the subscriber's address.",
                    fromKey: "address.state",
                },
                Zipcode: {
                    type: coda.ValueType.String,
                    description: "The zipcode of the subscriber's address.",
                    fromKey: "address.zipcode",
                },
                Country: {
                    type: coda.ValueType.String,
                    description: "The country of the subscriber's address.",
                    fromKey: "address.country",
                },
            },
            fromKey: "address",
            idProperty: "Address1",
        },
        CustomFields: {
            type: coda.ValueType.String,
            description: "The custom fields of the subscriber.",
            fromKey: "custom_fields",
        },
        Subscriptions: {
            type: coda.ValueType.Array,
            items: {
                type: coda.ValueType.Object,
                properties: {
                    SubscriptionId: {
                        type: coda.ValueType.String,
                        description: "The unique identifier for the subscription.",
                        fromKey: "subscription_id",
                    },
                    PublicationId: {
                        type: coda.ValueType.String,
                        description: "The unique identifier for the publication.",
                        fromKey: "publication_id",
                    },
                    PublicationName: {
                        type: coda.ValueType.String,
                        description: "The name of the publication.",
                        fromKey: "publication_name",
                    },
                    PostageTypeId: {
                        type: coda.ValueType.String,
                        description: "The unique identifier for the postage type.",
                        fromKey: "postage_type_id",
                    },
                    Status: {
                        type: coda.ValueType.String,
                        description: "The status of the subscription.",
                        fromKey: "status",
                    },
                    DigitalStatus: {
                        type: coda.ValueType.String,
                        description: "The digital status of the subscription.",
                        fromKey: "digital_status",
                    },
                    ExpirationDate: {
                        type: coda.ValueType.String,
                        description: "The expiration date of the subscription.",
                        fromKey: "expiration_date",
                    },
                    NeverExpires: {
                        type: coda.ValueType.Number,
                        description: "Whether the subscription never expires.",
                        fromKey: "never_expires",
                    },
                    Copies: {
                        type: coda.ValueType.Number,
                        description: "The number of copies of the subscription.",
                        fromKey: "copies",
                    },
                    PromoCode: {
                        type: coda.ValueType.String,
                        description: "The promo code of the subscription.",
                        fromKey: "promo_code",
                    },
                    IssuesRemaining: {
                        type: coda.ValueType.Number,
                        description: "The number of issues remaining for the subscription.",
                        fromKey: "issues_remaining",
                    },
                    GiftGiver: {
                        type: coda.ValueType.Object,
                        properties: {
                            AccountId: {
                                type: coda.ValueType.String,
                                description: "The unique identifier for the gift giver.",
                                fromKey: "giftgiver.account_id",
                            },
                        },
                    },
                    LastOrder: {
                        type: coda.ValueType.Object,
                        properties: {
                            OrderId: {
                                type: coda.ValueType.String,
                                description: "The unique identifier for the last order.",
                                fromKey: "last_order.order_id",
                            },
                            OrderDateTime: {
                                type: coda.ValueType.String,
                                description: "The date of the last order.",
                                fromKey: "last_order.order_date_time",
                            },
                            AmountDue: {
                                type: coda.ValueType.Number,
                                description: "The amount due for the last order.",
                                fromKey: "last_order.amount_due",
                            },
                            AmountPaid: {
                                type: coda.ValueType.Number,
                                description: "The amount paid for the last order.",
                                fromKey: "last_order.amount_paid",
                            },
                            Tax: {
                                type: coda.ValueType.Number,
                                description: "The tax for the last order.",
                                fromKey: "last_order.tax",
                            },
                            Issues: {
                                type: coda.ValueType.Number,
                                description: "The number of issues for the last order.",
                                fromKey: "last_order.issues",
                            },
                            Copies: {
                                type: coda.ValueType.Number,
                                description: "The number of copies for the last order.",
                                fromKey: "last_order.copies",
                            },
                            NeverExpires: {
                                type: coda.ValueType.Number,
                                description: "Whether the last order never expires.",
                                fromKey: "last_order.never_expires",
                            },
                            PostageTypeId: {
                                type: coda.ValueType.String,
                                description: "The unique identifier for the postage type of the last order.",
                                fromKey: "last_order.postage_type_id",
                            },
                            PromoCode: {
                                type: coda.ValueType.String,
                                description: "The promo code of the last order.",
                                fromKey: "last_order.promo_code",
                            },
                            PriceDescription: {
                                type: coda.ValueType.String,
                                description: "The price description of the last order.",
                                fromKey: "last_order.price_description",
                            },
                        },
                    },
                },
                idProperty: "SubscriptionId",
            },
            fromKey: "subscriptions",
        },
        Questions: {
            type: coda.ValueType.String,
            description: "The questions of the subscriber.",
            fromKey: "questions",
        },
    },
    idProperty: "AccountId",
    displayProperty: "AccountId",
});

const OptionalLimitParameter = coda.makeParameter({
    type: coda.ParameterType.Number,
    name: "limit",
    description: "The maximum number of results to return.",
    optional: true,
    suggestedValue: 100,
});

const OptionalEmailParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "email",
    description: "The email address to filter by.",
    optional: true,
});

const RequiredEmailParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "email",
    description: "The email address to filter by.",
});

pack.addSyncTable({
    name: "Subscribers",
    description: "Returns a list of your subscribers.",
    identityName: "Subscriber",
    schema: SubscriberSchema,
    formula: {
        name: "SyncSubscribers",
        description: "Syncs the list of subscribers from SimpleCirc.",
        parameters: [
            OptionalLimitParameter,
            OptionalEmailParameter,
        ],
        execute: async function ([Limit, Email], context) {

            let starting_after = context.sync.continuation?.starting_after;

            console.log("starting_after", starting_after);

            let batchSize = Limit || 10;

            let query_params = {
                limit: batchSize,
                email: Email,
                starting_after: starting_after || undefined,
            };

            if (starting_after) {
                query_params.starting_after = starting_after;
            }

            let url = coda.withQueryParams("https://simplecirc.com/api/v1.2/subscribers", query_params);

            let response = await context.fetcher.fetch({
                method: "GET",
                url: url,
            });

            let subscribers = response.body.subscribers;

            let subscribers_count = subscribers.length;

            let continuation;

            if (subscribers_count === batchSize) {
                continuation = {
                    starting_after: subscribers[subscribers_count - 1].account_id,
                };
            }

            return {
                result: subscribers,
                continuation: continuation,
            };
        },
    },
});


pack.addFormula({
    name: "Subscriber",
    description: "Returns a subscriber.",
    parameters: [
        RequiredEmailParameter,
        OptionalLimitParameter,
    ],
    resultType: coda.ValueType.Array,
    items: SubscriberSchema,
    execute: async function ([Email, Limit], context) {

        let starting_after = context.sync.continuation?.starting_after;

        let batchSize = Limit || 100;

        let query_params = {
            email: Email,
            starting_after: starting_after || undefined,
        };

        if (starting_after) {
            query_params.starting_after = starting_after;
        }

        let url = coda.withQueryParams("https://simplecirc.com/api/v1.2/subscribers", query_params);

        let response = await context.fetcher.fetch({
            method: "GET",
            url: url,
        });

        let subscribers = response.body.subscribers;

        let subscribers_count = subscribers.length;

        let continuation;

        if (subscribers_count === batchSize) {
            continuation = {
                starting_after: subscribers[subscribers_count - 1].account_id,
            };
        }

        return {
            result: subscribers,
            continuation: continuation,
        };
    },
});

