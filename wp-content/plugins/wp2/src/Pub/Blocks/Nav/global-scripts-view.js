"use strict";

document.addEventListener("DOMContentLoaded", function () {

  const collapseToggles = document.querySelectorAll(
    '[data-toggle-type="collapse"]'
  );

  collapseToggles.forEach(function (toggle) {

    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("aria-controls");
      const target = document.getElementById(targetId);

      if (target) {

        collapseToggles.forEach(function (otherToggle) {
          const otherTargetId = otherToggle.getAttribute("aria-controls");
          const otherTarget = document.getElementById(otherTargetId);

          if (otherTarget && otherTarget !== target) {
            otherTarget.classList.add("collapsing");
            otherTarget.classList.remove("show");

            otherToggle.setAttribute("aria-expanded", "false");

            setTimeout(function () {
              otherTarget.classList.remove("collapsing");
            }, 300);
          }
        });

        if (target.classList.contains("show")) {
          target.classList.add("collapsing");
          target.classList.remove("show");

          this.setAttribute("aria-expanded", "false");

          setTimeout(function () {
            target.classList.remove("collapsing");
          }, 300);
        } else {
          target.classList.add("show");
          this.setAttribute("aria-expanded", "true");
        }
      }
    });

    toggle.addEventListener("keyup", function (e) {
      if (e.key === "ArrowUp" || e.key === "ArrowDown") {
        e.preventDefault();
        const targetId = this.getAttribute("aria-controls");
        const target = document.getElementById(targetId);

        if (target) {

          collapseToggles.forEach(function (otherToggle) {
            const otherTargetId = otherToggle.getAttribute("aria-controls");
            const otherTarget = document.getElementById(otherTargetId);

            if (otherTarget && otherTarget !== target) {
              otherTarget.classList.add("collapsing");
              otherTarget.classList.remove("show");

              otherToggle.setAttribute("aria-expanded", "false");

              setTimeout(function () {
                otherTarget.classList.remove("collapsing");
              }, 300);
            }
          });

          if (target.classList.contains("show")) {
            target.classList.add("collapsing");
            target.classList.remove("show");

            this.setAttribute("aria-expanded", "false");

            setTimeout(function () {
              target.classList.remove("collapsing");
            }, 300);
          } else {
            target.classList.add("show");
            this.setAttribute("aria-expanded", "true");
          }
        }
      }
    });
  });

  const currentPageUrl = window.location.href;

  const navItems = document.querySelectorAll(".wp-block-navigation-item a");

  navItems.forEach(function (navItem) {
    if (navItem.href === currentPageUrl) {

      var parent = navItem.closest(".wp-block-navigation-item");

      parent.setAttribute("aria-current", "page");

    }
  });

});
