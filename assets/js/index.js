const prevBtn = document.querySelector(".prev-btn");
const nextBtn = document.querySelector(".next-btn");

function moveSlide(direction) {
  const activeSlide = document.querySelector(".active");
  const prevSlide = document.querySelector(".prev");
  const nextSlide = document.querySelector(".next");

  activeSlide.classList.remove("active");
  prevSlide.classList.remove("prev");
  nextSlide.classList.remove("next");

  if (direction === "prev") {
    activeSlide.classList.add("prev");
    prevSlide.classList.add("next");
    nextSlide.classList.add("active");
  } else if (direction === "next") {
    activeSlide.classList.add("next");
    prevSlide.classList.add("active");
    nextSlide.classList.add("prev");
  }
}

prevBtn.addEventListener("click", () => moveSlide("prev"));
nextBtn.addEventListener("click", () => moveSlide("next"));
