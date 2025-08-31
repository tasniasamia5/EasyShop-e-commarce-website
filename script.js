document.addEventListener("DOMContentLoaded", function () {
    const sliderList = document.querySelector('.header-slider ul');
    const slides = document.querySelectorAll('.header-slider ul li.slider-item');
    const totalSlides = slides.length;
    let currentSlide = 0;

    if (!sliderList || totalSlides === 0) {
        console.error("Slider elements not found. Check your HTML or selectors.");
        return;
    }

    sliderList.style.width = (totalSlides * 100) + '%';


    updateSlider();

    function updateSlider() {
        const offset = -(currentSlide * 100); // This correctly calculates the percentage to shift
        sliderList.style.transform = `translateX(${offset}%)`;
    }

    const controlPrev = document.querySelector('.control_prev');
    const controlNext = document.querySelector('.control_next');

    if (controlPrev) {
        controlPrev.addEventListener('click', function (e) {
            e.preventDefault();
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        });
    }

    if (controlNext) {
        controlNext.addEventListener('click', function (e) {
            e.preventDefault();
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        });
    }
});