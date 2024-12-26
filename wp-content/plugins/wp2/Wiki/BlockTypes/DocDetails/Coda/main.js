// Ensure MicroModal is initialized
if (typeof MicroModal === 'undefined') {
	MicroModal.init();
}

// ensure dom is loaded without conflicting with other scripts

document.addEventListener('DOMContentLoaded', function () {

	var codaPermissionButtons = document.querySelectorAll('[data-coda-docid] .wp-element-button');

	codaPermissionButtons.forEach(function (button) {

		var el = button;

		var user_id = user_data.id;

		var is_member = user_data.is_member;

		var $doc_link = 'https://coda.io/d/_d' + el.parentElement.getAttribute('data-coda-docid');

		var cookie_name = 'coda_doc_id_' + el.parentElement.getAttribute('data-coda-docid');

		var cookie = document.cookie.split(';').map(function (item) {
			return item.trim();
		}).filter(function (item) {
			return item.startsWith(cookie_name + '=');
		})[0];

		var cookieValue = cookie ? cookie.split('=')[1] : '';

		if (user_id) {
			if (is_member) {
				if (cookieValue === 'true') {
					el.innerHTML = 'Already Shared';
				} else {
					el.innerHTML = 'Request Access';
				}
			} else {
				el.innerHTML = 'Become a Member';
			}
		} else {
			el.innerHTML = 'Login Required';
		}

		button.addEventListener('click', function (e) {

			e.preventDefault();

			var docId = el.parentElement.getAttribute('data-coda-docid');

			codaAddPermission(el, docId, $doc_link);
			
		});
	});

});

// function for adding permission

function codaAddPermission(el, docId, $doc_link) {

	if (!docId) {
		return;
	}

	var user_id = user_data.id;

	if (!user_id) {

		var modal_signin = document.getElementById('modal-signin');

		if (modal_signin) {
			MicroModal.show('modal-signin');
			return;
		}

		return;
	}

	var is_member = user_data.is_member;

	if (!is_member) {

		var modal_membership = document.getElementById('modal-membership');

		if (modal_membership) {
			MicroModal.show('modal-membership');
			return;
		}

		return;
	}

	if (el.innerHTML === 'Already Shared' || el.innerHTML === 'Just Shared') {
		// open coda doc in new tab
		window.open($doc_link, '_blank');
		return;
	}

	var current_domain = window.location.hostname;
	var data = {
		'docId': docId,
		'user_id': user_id
	};
	var url = 'https://' + current_domain + '/wp-json/coda/v1/add-permission';
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url, true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.send(JSON.stringify(data));
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4) {
			if (xhr.status === 200) {
				// update button text
				el.innerHTML = 'Just Shared';
				// disable button
				el.classList.add('disabled');
				el.setAttribute('disabled', 'disabled');
				// set cookie using coda_doc_id_ + docId = true
				var cookie_name = 'coda_doc_id_' + docId;
				var cookie_value = true;
				// write cookie
				document.cookie = cookie_name + '=' + cookie_value + '; path=/';
			} else {
				// Handle error
				console.error('Error: Unable to add permission');
			}
		}
	};

	return;

}