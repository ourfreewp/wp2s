import 'blockstudio/@splidejs/splide@4.1.4/dist/css/splide.min.css';
import { Splide } from "blockstudio/@splidejs/splide@4.1.4";
import { URLHash } from "blockstudio/@splidejs/splide-extension-url-hash@0.3.0";

document.addEventListener("DOMContentLoaded", function () {

  if (typeof Splide === "undefined") {
    console.error("Splide is not loaded or available");
    return;
  }

  var slideshows = document.querySelectorAll(".slideshow");

  slideshows.forEach(function (el) {

    var slideshow = el;

    var primary          = slideshow.querySelector(".slideshow__primary");
    var primaryslideshow = primary ? new Splide(primary) : null;

    var secondary          = slideshow.querySelector(".slideshow__secondary");
    var secondaryslideshow = secondary ? new Splide(secondary) : null;

    var tertiary          = slideshow.querySelector(".slideshow__tertiary");
    var tertiaryslideshow = tertiary ? new Splide(tertiary) : null;

    secondaryslideshow && primaryslideshow.sync(secondaryslideshow);
    tertiaryslideshow && primaryslideshow.sync(tertiaryslideshow);

    primaryslideshow && primaryslideshow.mount({ URLHash });
    secondaryslideshow && secondaryslideshow.mount({ URLHash });
    tertiaryslideshow && tertiaryslideshow.mount({ URLHash });

  });

});
