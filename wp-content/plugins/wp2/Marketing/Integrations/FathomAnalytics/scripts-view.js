document.addEventListener("DOMContentLoaded", function() {
  // Wait for fathom to become available
  var checkFathomInterval = setInterval(function() {
    if (typeof fathom !== "undefined") {
      clearInterval(checkFathomInterval);
      attachKlaviyoFormsListener();
    }
  }, 100);

  // Attach Klaviyo Forms Event Listener
  function attachKlaviyoFormsListener() {
    window.addEventListener("klaviyoForms", function(e) {
      var eventType = e.detail.type;
      var formId = e.detail.formId;

      if (eventType === "open") {
        if (typeof fathom !== "undefined") {
          fathom.trackGoal("ID3NQPZW", formId);
        }
        console.log("Tracked: Klaviyo Form Opened (Form ID: " + formId + ")");
      } else if (eventType === "embedOpen") {
        if (typeof fathom !== "undefined") {
          fathom.trackGoal("CWO44W6L", formId);
        }
        console.log("Tracked: Klaviyo Form Embed Opened (Form ID: " + formId + ")");
      } else if (eventType === "close") {
        if (typeof fathom !== "undefined") {
          fathom.trackGoal("AZRFN26M", formId);
        }
        console.log("Tracked: Klaviyo Form Closed (Form ID: " + formId + ")");
      } else if (eventType === "redirectedToUrl") {
        if (typeof fathom !== "undefined") {
          fathom.trackGoal("NO530ODR", formId);
        }
        console.log("Tracked: Klaviyo Form Redirected to URL (Form ID: " + formId + ")");
      } else if (eventType === "submit") {
        if (typeof fathom !== "undefined") {
          fathom.trackGoal("YSHVPHFQ", formId);
        }
        console.log("Tracked: Klaviyo Form Submitted (Form ID: " + formId + ")");
      } else if (eventType === "stepSubmit") {
        if (typeof fathom !== "undefined") {
          fathom.trackGoal("ELWVHYFY", formId);
        }
        console.log("Tracked: Klaviyo Form Step Submitted (Form ID: " + formId + ")");
      }
    });
  }
});
