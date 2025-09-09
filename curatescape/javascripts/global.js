var theme_name = "curatescape";
jQuery(document).ready(function ($) {

  // SMART PUNCTUATION
  function curlies(element) {
    function smarten(text) {
      return (
        text
          /* opening singles */
          .replace(/(^|[-\u2014\s(\["])'/g, "$1\u2018")
  
          /* closing singles & apostrophes */
          .replace(/'/g, "\u2019")
  
          /* opening doubles */
          .replace(/(^|[-\u2014/\[(\u2018\s])"/g, "$1\u201c")
  
          /* closing doubles */
          .replace(/"/g, "\u201d")
      );
    }
    var children = element.children;
    if (children.length) {
      for (var i = 0, l = children.length; i < l; i++) {
        curlies(children[i]);
      }
    } else {
      element.innerHTML = smarten(element.innerHTML);
    }
  }
  curlies(document.body);
  
  // ============================
  // TOUR IMAGES
  if ($(".fetch-tour-image").length) {
    var tours_json =
      window.location.protocol +
      "//" +
      window.location.hostname +
      "/tours/browse?output=mobile-json";
    $.getJSON(tours_json, function (data) {
      data.tours.forEach(function (tour) {
        if (tour.tour_img && tour.id) {
          $(
            ".fetch-tour-image[data-tour-id=" +
              tour.id +
              "] .tour-image-container"
          ).css(
            "background-image",
            "url(" +
              tour.tour_img.replace("fullsize", "square_thumbnails") +
              ")"
          );
        }
      });
    });
  }


});
