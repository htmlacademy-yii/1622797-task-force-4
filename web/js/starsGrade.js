let starRating = document.querySelector(".active-stars");

if (starRating) {
  starRating.addEventListener("click", function(event) {
    let stars = event.currentTarget.childNodes;
    let rating = 0;

    for (let i = 0; i < stars.length; i++) {
      let element = stars[i];

      if (element.nodeName === "SPAN") {
        element.className = "fill-star";
        rating++;
      }

      if (element === event.target) {
        break;
      }
    }

    let inputField = document.getElementById('createreviewform-rate');
    inputField.value = rating;
  });
}
