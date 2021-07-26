

const myCustomSlider = document.querySelectorAll('.swiper-container');

for( i=0; i< myCustomSlider.length; i++ ) {
  
  myCustomSlider[i].classList.add('swiper-container-' + i);

  const iD = myCustomSlider[i].getAttribute('id');

  const slider = new Swiper('.swiper-container-' + i, {
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    spaceBetween: 50,
    speed: 500,
    initialSlide: iD === "weather_history" ? 4 : 0,
  });

}
