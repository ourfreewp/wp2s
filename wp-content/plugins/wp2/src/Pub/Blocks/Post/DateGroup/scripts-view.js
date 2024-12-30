document.addEventListener("DOMContentLoaded", function () {

	var dates = document.querySelectorAll(".wp-block-onthewater-post-dateline-date");

	dates.forEach(function (el) {
		var dateElement = el;

		var time = dateElement.querySelector("time");

		if (time) {

			var datetime = time.getAttribute("datetime");

			var nicedate = dayjs().to(dayjs(datetime));

			nicedate = nicedate.charAt(0).toUpperCase() + nicedate.slice(1);

			var nicedateElement = dateElement.querySelector(".wp-block-onthewater-post-dateline-nicedate");

			if (nicedateElement) {
				nicedateElement.innerHTML = nicedate;
				nicedateElement.classList.remove("visually-hidden");

				var datetimeElement = dateElement.querySelector(".wp-block-onthewater-post-dateline-datetime");

				if (datetimeElement) {
					datetimeElement.classList.add("visually-hidden");
				}
			}

		}

	});

});