document.addEventListener("DOMContentLoaded", function () {
  // Function to handle the button click and initiate the subscription process
  function handleSignupButtonClick(event) {
    const form = event.target.closest('.klaviyo-form'); // Get the parent form of the clicked button
    if (!form) return; // If the button is not within a form, exit the function

    // Get the email value from the hidden input field (WordPress user's email)
    const emailInput = form.querySelector('.klaviyo-email-input');
    const email = emailInput.value; // Use the input value if present, else use the user's email

	// Check if the email input is empty
    if (email === '') {
     	alert('Please enter your email before submitting the form.');
      return;
    }
	  
    const listId = form.querySelector('.klaviyo-form-button').getAttribute('data-list-id');
    const sourceName = form.querySelector('.klaviyo-form-button').getAttribute('data-source-name');
    const apiKey = form.querySelector('.klaviyo-form-button').getAttribute('data-api-key');

    const options = {
      method: 'POST',
      headers: { 'revision': '2023-07-15', 'content-type': 'application/json' },
      body: JSON.stringify({
        data: {
          type: 'subscription',
          attributes: {
            custom_source: sourceName,
            profile: {
              data: {
                type: 'profile',
                attributes: {
                  email: email,
                },
              },
            },
          },
          relationships: { list: { data: { type: 'list', id: listId } } },
        },
      }),
    };

    fetch(`https://a.klaviyo.com/client/subscriptions/?company_id=${apiKey}`, options)
      .then(response => {
        if (response.status === 202) {
          
          // Handle the 202 response here (success response)
          console.log('Success Response (202)');

          // Add the 'klaviyo-form-success' class to the form
          form.classList.add('klaviyo-form-success');

          // Disable the button
          const klaviyoButton = form.querySelector('.klaviyo-form-button');
          klaviyoButton.disabled = true;

          // You may perform additional actions or display a success message
        } else {
          // Handle other status codes (throw an error)
          throw new Error('Unexpected response from the server');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        // Handle the error here (for non-202 responses)
      });
  }

  // Get all the forms with class '.klaviyo-form' and attach event listeners to their buttons
  const klaviyoForms = document.querySelectorAll('.klaviyo-form');
  klaviyoForms.forEach((form) => {
    const klaviyoButton = form.querySelector('.klaviyo-form-button');
    klaviyoButton.addEventListener('click', handleSignupButtonClick);
  });
});


window.onload = function() {
	console.log("Page has loaded!");

	if (typeof Altis !== 'undefined') {
		Altis.Analytics.onReady(function () {
			// Check if Altis.Analytics.getEndpoint().Attributes.KlaviyoForms exists
			if (
				Altis.Analytics.getEndpoint() &&
				Altis.Analytics.getEndpoint().Attributes &&
				Altis.Analytics.getEndpoint().Attributes.KlaviyoForms
			) {
				// If it exists, return the value of Altis.Analytics.getEndpoint().Attributes.KlaviyoForms
				Altis.Analytics.registerAttribute('KlaviyoForms', function () {
					return Altis.Analytics.getEndpoint().Attributes.KlaviyoForms;
				});
			} else {
				// If it doesn't exist, return nothing (or any default value you prefer)
				Altis.Analytics.registerAttribute('KlaviyoForms', function () {
					// This is an example of a callback that returns nothing
					return '';
				});
			}
		});
	}
};

window.addEventListener("klaviyoForms", function(e) {
	if (e.detail.type == 'submit') {
		console.log('Klaviyo Form Submitted');

		if (typeof Altis !== 'undefined') {
			Altis.Analytics.onReady(function () {
				console.log('Altis Ready for Data');

				if (Altis.Analytics.getEndpoint()) {
					console.log(Altis.Analytics.getEndpoint());

					var newFormId = e.detail.formId;

					// Get existing form IDs or initialize an empty array if it doesn't exist.
					var existingFormIds = Altis.Analytics.getEndpoint().Attributes.KlaviyoForms || [];

					// Check if the newFormId is not already in the existingFormIds array.
					if (existingFormIds.indexOf(newFormId) === -1) {
						existingFormIds.push(newFormId);
					}

					// Convert the existingFormIds array to a comma-separated string.
					var existingFormIdsString = existingFormIds.join(',');

					// Update the KlaviyoForms attribute with the new string.
					Altis.Analytics.updateEndpoint({
						"Attributes": {
							"KlaviyoForms": existingFormIdsString
						}
					});
				}
			});
		}
	}
});
