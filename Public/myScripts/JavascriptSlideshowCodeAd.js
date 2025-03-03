var slideIndexAd = 1;
showSlidesAd(slideIndex);

function plusSlidesAd(n) {
  showSlides(slideIndex += n);
}

function currentSlideAd(n) {
  showSlides(slideIndex = n);
}

function showSlidesAd(n) {
  var i;
  var slides = document.getElementsByClassName("mySlidesAd");
  var dots = document.getElementsByClassName("dotAd");
  if (n > slides.length) {slideIndexAd = 1} 
  if (n < 1) {slideIndexAd = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none"; 
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndexAd-1].style.display = "block"; 
  dots[slideIndexAd-1].className += " active";
}