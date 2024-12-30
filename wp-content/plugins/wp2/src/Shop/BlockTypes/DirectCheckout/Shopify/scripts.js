document.querySelector('.wp-block-button [data-merchandiseId]').addEventListener('click', function () {

	let merchandiseId = this.getAttribute('data-merchandiseId');

	wp.hooks.doAction('do.directCheckout', {
		lines: [
			{
				attributes: [],
				merchandiseId: 'gid://shopify/ProductVariant/' + merchandiseId,
				quantity: 1
			},
		],
		attributes: false,
		settings: {
			linkTarget: '_blank',
		},
	})
});