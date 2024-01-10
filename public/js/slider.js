const prev = document.querySelector(".prev");
const next = document.querySelector(".next");
const slides = document.querySelectorAll(".slide");
const slideNumber = slides.length;
if (prev && next) {
  prev.addEventListener("click", () => {
    let currentSlide = document.querySelector(".slider .active");
    if (Number(currentSlide.id.split("_")[1]) === 1) {
      currentSlide.classList.remove("active");
      currentSlide.classList.add("d-none");
      let nextSlide = document.querySelector(`#slide_${slideNumber}`);
      nextSlide.classList.remove("d-none");
      nextSlide.classList.add("active");
    } else {
      currentSlide.classList.remove("active");
      currentSlide.classList.add("d-none");
      let nextSlide = document.querySelector(
        `#slide_${Number(currentSlide.id.split("_")[1]) - 1}`
      );
      nextSlide.classList.remove("d-none");
      nextSlide.classList.add("active");
    }
  });
  next.addEventListener("click", () => {
    let currentSlide = document.querySelector(".slider .active");
    if (Number(currentSlide.id.split("_")[1]) === slideNumber) {
      currentSlide.classList.remove("active");
      currentSlide.classList.add("d-none");
      let nextSlide = document.querySelector(`#slide_1`);
      nextSlide.classList.remove("d-none");
      nextSlide.classList.add("active");
    } else {
      currentSlide.classList.remove("active");
      currentSlide.classList.add("d-none");
      let nextSlide = document.querySelector(
        `#slide_${Number(currentSlide.id.split("_")[1]) + 1}`
      );
      nextSlide.classList.remove("d-none");
      nextSlide.classList.add("active");
    }
  });
  const screenWidth = window.innerWidth;
  if (screenWidth < 555) {
    let slideFlag = false;
    slides.forEach((slide) => {
      slide.addEventListener("click", () => {
        let images = document.querySelectorAll(".detail-container img");
        images.forEach((image) => {
          if (!slideFlag) {
            image.style.width = "100%";
            image.style.height = "100%";
            image.style.zIndex = "5";
          } else {
            image.style.width = "100%";
            image.style.height = "30vh";
            image.style.zIndex = "1";
          }
        });
        slideFlag = !slideFlag;
      });
    });
  }
}
