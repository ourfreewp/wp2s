import * as coda from "@codahq/packs-sdk";

import * as cheerio from 'cheerio';
import { title } from "process";

export const pack = coda.newPack();

pack.setUserAuthentication({
	type: coda.AuthenticationType.QueryParamToken,
	paramName: "access_key"
});

// Allow the pack to make requests to Todoist.
pack.addNetworkDomain("scrapestack.com");

const UrlParameter = coda.makeParameter({
	type: coda.ParameterType.String,
	name: "url",
	description: "The URL of the page to scrape",
});

// 2024-08-12 Meet The Frillback, the World’s Fanciest Pigeon Breed Animals | slideshow | Vanessa Barros Andrade

const PostSchema = coda.makeObjectSchema({
	properties: {
		PostId: {
			type: coda.ValueType.String,
			description: "The ID of the post",
			fromKey: "id",
		},
		Title: {
			type: coda.ValueType.String,
			description: "The title of the post",
			fromKey: "title",
		},
		Type: {
			type: coda.ValueType.String,
			description: "The type of the post",
			fromKey: "type",
		},
		Date: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Date,
			description: "The date of the post",
			fromKey: "date",
		},
		Link: {
			type: coda.ValueType.String,
			codaType: coda.ValueHintType.Url,
			description: "The link to the post",
			fromKey: "link",
		},
		Author: {
			type: coda.ValueType.String,
			description: "The author of the post",
			fromKey: "author",
		},
		Slug: {
			type: coda.ValueType.String,
			description: "The slug of the post",
			fromKey: "slug",
		},
	},
	displayProperty: "Title",
	idProperty: "PostId",
});


function scrape_posts(html) {

	let parser = cheerio.load(html);

	let posts = [];

	let body = parser("body");

	let list = body.find("ol:first-of-type");

	list.find("li").each((i, el) => {

		let title = parser(el).find("a").text().trim();
		let link = parser(el).find("a").attr("href").trim();
		let slug = link.replace("https://oddnewsshow.com/", "").trim();
		let date = parser(el).find("date").text().trim();
		let type = parser(el).find("span.template").text().trim();
		let author = parser(el).find("span.author").text().trim();

		let post = {
			id: i + '-' + slug,
			slug: slug,
			link: link,
			title: title,
			type: type,
			date: date,
			author: author,
		};
		posts.push(post);
	}
	);
	return posts;
}

pack.addSyncTable({
	name: "Posts",
	identityName: "Post",
	schema: PostSchema,
	formula: {
		name: "SyncPosts",
		description: "Syncs the posts.",
		parameters: [
			UrlParameter,
		],
		execute: async function ([Url], context) {
			let url = coda.withQueryParams("https://api.scrapestack.com/scrape", {
				"url": Url,
			});
			let response = await context.fetcher.fetch({
				method: "GET",
				url: url,
			});
			let post_html = response.body;

			let posts = scrape_posts(post_html);

			return {
				result: posts,
			};
		},
	},
});

function parse_data(raw_html: string) {
	let parser = cheerio.load(raw_html);

	// For each, always trim the text

	// title tag
	let meta_title: string | undefined;

	meta_title = parser("head title").text();

	if (meta_title) {
	  meta_title = meta_title.trim();
	}

	// split off " | Odd News Show" from the end
	if (meta_title) {
	  meta_title = meta_title.replace(" | Odd News Show", "").trim();
	}

	// meta name="description" 
	let meta_description: string | undefined;

	meta_description = parser("head meta[name='description']").attr("content");

	// decode all html entities
	if (meta_description) {
	  meta_description = cheerio.load(meta_description).text().trim();
	}

	// meta property="og:image" content
	let meta_image: string | undefined;

	meta_image = parser("head meta[property='og:image']").attr("content");
	if (meta_image) {
		meta_image = meta_image.trim();
	}

	// meta property="og:type" content
	let meta_type: string | undefined;

	meta_type = parser("head meta[property='og:type']").attr("content");
	if (meta_type) {
		meta_type = meta_type.trim();
	}

	// meta property="og:url" content
	let meta_url: string | undefined;

	meta_url = parser("head meta[property='og:url']").attr("content");
	if (meta_url) {
		meta_url = meta_url.trim();
	}

	// body classes
	let body_classes: string | undefined;

	body_classes = parser("body").attr("class");
	if (body_classes) {
		body_classes = body_classes.trim();
	}

	// type by parsing body classes `b-slideshow` or `b-article` and we just want `slideshow` or `article`
	let type: string;

	if (body_classes?.includes("b-slideshow")) {
		type = "slideshow";
	} else if (body_classes?.includes("b-article")) {
		type = "article";
	} else {
		type = "unknown";
	}

	// .category text
	let category_name = parser("main .category").text().trim();
	
	// if .category a exists, get href
	let category_link: string | undefined;
	
	if (parser("main .category a").length > 0) {
		category_link = parser("main .category a").attr("href")?.trim();
	}
	
	let author_name: string;
	let author_link: string | undefined;
	
	if (parser("main .info a").length > 0) {
		author_name = parser("main .info a").text().trim();
		author_link = parser("main .info a").attr("href")?.trim();
	} else {
		author_name = parser("main .info").text().trim();
		// after "By " and before " ·" text
		author_name = author_name.replace("By ", "").split(" ·")[0].trim();
		author_link = "";
	}
	
	// h1 text
	let post_title = parser("main h1").text().trim();
	
	// .abstract text strip html
	let post_excerpt = parser("main .abstract").text().trim();

	// ul.tags > li > a
	let tags: { name: string, link: string }[] = [];

	parser("main ul.tags li a").each((_, el) => {
		let name = parser(el).text();
		let link = parser(el).attr("href");
		if (name && link) {
			let tag = {
				name: name.trim(),
				link: link.trim(),
			};
			tags.push(tag);
		}
	});

	// main > figure:first-of-type > img
	let post_image_figure = parser("main figure:first-of-type");

	let post_image = {
		src: post_image_figure.find("img").attr("src")?.trim(),
		alt: post_image_figure.find("img").attr("alt")?.trim(),
		title: post_image_figure.find("img").attr("title")?.trim() || post_image_figure.find("h2").text()?.trim(),
		byline: post_image_figure.find(".copyright")?.text()?.trim(),
		filename: post_image_figure.find("img").attr("src")?.split("/")?.pop()?.trim(),
	};

	let payload = {
		post_meta: {
			title: meta_title,
			description: meta_description,
			image: meta_image,
			type: meta_type,
			url: meta_url,
		},
		post_type: type,
		post_category: {
			name: category_name,
			link: category_link,
		},
		post_author: {
			name: author_name,
			link: author_link,
		},
		post_title: post_title,
		post_excerpt: post_excerpt,
		post_tags: tags,
		post_image: post_image,
	};

	// always return a valid JSON object, even if empty or null or undefined

	return payload || {};
}

function handleError(error: Error) {
	console.error(error);
	return "An error occurred while scraping the URL.";
}

pack.addFormula({
	name: "ScrapeUrl",
	description: "Scrape a url and returns post data",
	parameters: [
		UrlParameter,
	],
	resultType: coda.ValueType.String,
	isAction: true,
	onError: handleError,
	execute: async function ([Url], context) {
		try {
			let url = coda.withQueryParams("https://api.scrapestack.com/scrape", {
				"url": Url,
			});
			let response = await context.fetcher.fetch({
				method: "GET",
				url: url,
			});
			if (response.status !== 200) {
				throw new Error(`Failed to fetch URL. Status: ${response.status}`);
			}
			let raw_html = response.body;

			let payload = parse_data(raw_html) || {};

			return JSON.stringify(payload);
		} catch (error) {
			console.error(error);
			throw new Error("An error occurred while scraping the URL.");
		}
	},
});
