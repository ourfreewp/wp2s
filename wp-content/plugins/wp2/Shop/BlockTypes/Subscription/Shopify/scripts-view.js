console.log('Subscriptions main.js loaded!');

// On load, find all [data-shopify-variant-id] .wp-element-button and for each one, add a click event listener that logs to console.
document.addEventListener('DOMContentLoaded', function () {

	// check if url has 'discount' query parameter

	var urlParams = new URLSearchParams(window.location.search);

	var discount = urlParams.get('discount');

	if (discount) {
		console.log('Discount:', discount);
	}


	var monthlyButtonContent = document.querySelector('.freq-item--monthly');

	var yearyButtonContent = document.querySelector('.freq-item--yearly');

	var freqPreference = getCookie('freq_preference');

	console.log('Frequency preference:', freqPreference);

	// if not found, set to 'yearly' otherwise get the value ensure it is either `yearly` or `monthly`

	if (!freqPreference) {
		setCookie('freq_preference', 'yearly');
		freqPreference = 'yearly';
	} else {
		if (freqPreference !== 'yearly' && freqPreference !== 'monthly') {
			setCookie('freq_preference', 'yearly');
			freqPreference = 'yearly';
		}
	}

	// Find all [data-shopify-variant-id] .wp-element-button
	var buyButtons = document.querySelectorAll('[data-shopify-merchandiseId] .wp-element-button');

	// For each one, add a click event listener that logs to console.
	buyButtons.forEach(function (button) {

		button.addEventListener('click', function (e) {

			console.log('Button clicked!');

			var userEmail = wp_shopify.userEmail;

			if (!userEmail) {
				console.error("User email not found");

				e.preventDefault();

				MicroModal.show('modal-signin');

				return;
			}

			var event = e;
			var button = event.target;
			var parent = button.parentElement;
			var merchandiseId = parent.getAttribute("data-shopify-merchandiseId");

			if (!merchandiseId) {
				console.error("Merchandise ID not found");
				return;
			}

			var sellingPlanId = parent.getAttribute("data-shopify-sellingPlanId");

			if (!sellingPlanId) {
				console.error("Selling plan ID not found");
				return;
			}

			var quantity = 1;

			var encodedMerchandiseId = btoa(`gid://shopify/ProductVariant/${merchandiseId}`);

			console.log('Encoded merchandise ID:', encodedMerchandiseId);

			var encodedSellingPlanId = btoa(`gid://shopify/SellingPlan/${sellingPlanId}`);

			console.log('Encoded selling plan ID:', encodedSellingPlanId);

			// input

			var query = `mutation Mutation {
				cartCreate(
					input: {
						buyerIdentity: {
							email: "${wp_shopify.userEmail}"
						}
						lines: [
							{
								merchandiseId: "${encodedMerchandiseId}"
								quantity: ${quantity}
								sellingPlanId: "${encodedSellingPlanId}"
							}
						]
					}
				) {
					cart {
						id
						checkoutUrl
					}
					userErrors {
						code
						field
						message
					}
				}
			}`;

			getCartLink(query).then(function (data) {
				payload = data.data.cartCreate;
				cart = payload.cart;
				checkoutUrl = cart.checkoutUrl;
				window.location.href = checkoutUrl;
				return;
			});

			return;

		});

	});

	var frequencyButtons = document.querySelectorAll('[data-freq-toggle] .wp-element-button');

	// finc the button that matches the frquency preference and add the active class to it

	frequencyButtons.forEach(function (button) {

		var parent = button.parentElement;

		var freqToggle = parent.getAttribute("data-freq-toggle");

		if (!freqToggle) {
			console.error("Frequency toggle not found");
			return;
		}

		if (freqToggle === freqPreference) {
			button.parentElement.classList.add('active');
		}

		button.addEventListener('click', function (e) {

			var event = e;
			var button = event.target;
			var parent = button.parentElement;
			var freqToggle = parent.getAttribute("data-freq-toggle");

			if (!freqToggle) {
				console.error("Frequency toggle not found");
				return;
			}

			if (freqToggle === 'yearly') {
				setCookie('freq_preference', 'yearly');
				button.parentElement.classList.add('active');
				var monthlyButton = document.querySelector('[data-freq-toggle="monthly"] .wp-element-button');
				monthlyButton.parentElement.classList.remove('active');
				monthlyButtonContent.classList.add('freq-hidden');
				yearyButtonContent.classList.remove('freq-hidden');

				// aria-hidden="false"

				// set aria-hidden="false" on the monthly and aria-hidden="true" on the yearly

				monthlyButtonContent.setAttribute('aria-hidden', 'true');
				yearyButtonContent.setAttribute('aria-hidden', 'false');


			} else if (freqToggle === 'monthly') {
				setCookie('freq_preference', 'monthly');
				button.parentElement.classList.add('active');
				var yearlyButton = document.querySelector('[data-freq-toggle="yearly"] .wp-element-button');
				yearlyButton.parentElement.classList.remove('active');
				yearyButtonContent.classList.add('freq-hidden');
				monthlyButtonContent.classList.remove('freq-hidden');

				// set aria-hidden="false" on the yearly and aria-hidden="true" on the monthly

				yearyButtonContent.setAttribute('aria-hidden', 'true');
				monthlyButtonContent.setAttribute('aria-hidden', 'false');
				
			}	


		});

	});



});

// Request the cart link

async function getCartLink(query) {
	// get shop token from wp_shopify object

	console.log('Requesting cart link...');

	var shopToken = wp_shopify.shopToken;
	var shopDomain = wp_shopify.shopDomain;
	var shopDiscount = wp_shopify.shopDiscount;

	if (!shopToken) {
		console.error("Shop token not found");
		return;
	}

	try {

		console.log('Fetching cart link...');

		var response = await fetch(`https://${shopDomain}/api/2024-01/graphql.json`, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				"X-Shopify-Storefront-Access-Token": shopToken
			},
			body: JSON.stringify({ query: query })
		});

	} catch (error) {
		console.error("Error fetching cart link:", error);
		return;
	}

	var data = response.json();

	return data;

}

async function getCookie(name) {
	var cookieArr = document.cookie.split(';');
	for (var i = 0; i < cookieArr.length; i++) {
		var cookiePair = cookieArr[i].split('=');
		if (name === cookiePair[0].trim()) {
			return decodeURIComponent(cookiePair[1]);
		}
	}
	return null;
}

async function setCookie(name, value) {
	document.cookie = `${name}=${value};path=/`;
	return;
}