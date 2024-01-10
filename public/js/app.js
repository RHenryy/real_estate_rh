// Mobile Navbar burger menu event listeners
const burger = document.getElementById("burger-trigger");
const mobileNav = document.getElementById("mobile-nav");
const modalNav = document.querySelector(".modal-nav");
const closeNav = document.querySelector(".close-modal-nav");
burger.addEventListener("click", () => {
  modalNav.classList.remove("d-none");
});
closeNav.addEventListener("click", () => {
  modalNav.classList.add("translateLeft");
  setTimeout(() => {
    modalNav.classList.add("d-none");
    modalNav.classList.remove("translateLeft");
  }, 310);
});
const filters = document.querySelectorAll(".flex-users-choice>p");
if (filters) {
  filters.forEach((filter) => {
    filter.addEventListener("click", () => {
      if (filter.classList.contains("active-filter")) {
        return;
      }
      let id = filter.classList[0];
      let target = document.getElementById(id);
      if (!target) return;
      filter.classList.toggle("active-filter");
      if (target.classList.contains("d-none")) {
        target.classList.remove("d-none");
      }
      filters.forEach((filter) => {
        if (filter.classList[0] !== id) {
          filter.classList.remove("active-filter");
          let target = document.getElementById(filter.classList[0]);
          if (!target.classList.contains("d-none")) {
            target.classList.add("d-none");
          }
        }
      });
    });
  });
}
