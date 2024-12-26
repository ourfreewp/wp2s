addEventListener('DOMContentLoaded', function () {

	if (typeof MicroModal !== 'undefined') {

		// for each link with href containing `signin' prevent defaults and trigger modal

		document.querySelectorAll('.wp-block-navigation a[href*="signin"]').forEach(function (link) {

			link.addEventListener('click', function (e) {

				e.preventDefault();

				MicroModal.show('modal-signin');

			});

		});

		// data-custom-trigger="modal-signup"

		// given the modal-signup click, close modal-signin and open modal-signup

		// data-custom-trigger="modal-signin"

		// given the modal-signin click, close modal-signup and open modal-signin

		this.document.querySelectorAll('[data-custom-trigger]').forEach(function (link) {
			
			link.addEventListener('click', function (e) {

				e.preventDefault();

				if (link.dataset.customTrigger === 'modal-signin') {

					MicroModal.close('modal-signup');
					MicroModal.show('modal-signin');

				}

				if (link.dataset.customTrigger === 'modal-signup') {

					MicroModal.close('modal-signin');
					MicroModal.show('modal-signup');

				}

			});

		});

	}

});

