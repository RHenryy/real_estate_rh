// reveal password
let revealPassBtn = document.querySelectorAll(".show-pass");
revealPassBtn.forEach((btn) => {
  btn.addEventListener("click", function () {
    if (btn.nextElementSibling.type === "password") {
      btn.classList.remove("fa-eye");
      btn.classList.add("fa-eye-slash");
      btn.nextElementSibling.type = "text";
    } else {
      btn.classList.remove("fa-eye-slash");
      btn.classList.add("fa-eye");
      btn.nextElementSibling.type = "password";
    }
  });
});
// Verify form inputs on submit
// const form = document.querySelector("form");
// const inputs = document.querySelectorAll("input");
// form.addEventListener("submit", function (e) {
//   inputs.forEach((input) => {
//     if (
//       input.value === "" &&
//       input.type !== "file" &&
//       input.name !== "confirm_email" &&
//       input.name !== "confirm_property" &&
//       input.name !== "confirm-property"
//     ) {
//       e.preventDefault();
//       input.classList.add("input-error");
//     }
//   });
// });
// Preview images property form
const imageInputProperty = document.querySelector("#property_image");
if (imageInputProperty) {
  imageInputProperty.addEventListener("change", function () {
    const preview = document.querySelector(".show-images");
    const files = imageInputProperty.files;
    document.querySelectorAll(".class-form.property img").forEach((img) => {
      img.remove();
    });
    for (let i = 0; i < files.length; i++) {
      const img = document.createElement("img");
      img.src = URL.createObjectURL(files[i]);
      img.style.width = 100 + "px";
      img.style.height = 100 + "px";
      preview.appendChild(img);
    }
  });
}
// Preview image agency form
const previewAgence = document.querySelector("#agency_image_preview");
const inputFileAgence = document.querySelector("#agency_image");
if (previewAgence && inputFileAgence) {
  inputFileAgence.addEventListener("change", function () {
    const file = inputFileAgence.files[0];
    previewAgence.src = URL.createObjectURL(file);
  });
}
// Preview image user form
const fileInputUser = document.querySelector("input[type=file]");
let currentImageUser = document.querySelector(
  ".card-container .user-image img"
);
if (fileInputUser && currentImageUser) {
  fileInputUser.addEventListener("change", function () {
    const newSrc = URL.createObjectURL(fileInputUser.files[0]);
    currentImageUser.src = newSrc;
  });
}
// function to preview images on newly created inputs
function previewImages(inputFile) {
  inputFile.addEventListener("change", function () {
    let id = inputFile.id.replace("input_", "");
    let preview = document.querySelector(`#${id}`);
    preview.src = URL.createObjectURL(inputFile.files[0]);
  });
}
// event listener on all existing input files
const inputFiles = document.querySelectorAll("input[type=file]");
if (inputFiles.length >= 1) {
  inputFiles.forEach((inputFile) => {
    previewImages(inputFile);
  });
}
// Generate file input property edit
const addImageButton = document.querySelector(".addFile");
if (addImageButton) {
  addImageButton.addEventListener("click", function () {
    let numberOfFileInputs =
      document.querySelectorAll("input[type=file]").length;
    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.name = `image_${numberOfFileInputs + 1}`;
    fileInput.id = `input_image_${numberOfFileInputs + 1}`;
    let divInputGroup = document.createElement("div");
    let img = document.createElement("img");
    img.setAttribute("id", `image_${numberOfFileInputs + 1}`);
    img.width = 100;
    img.height = 100;
    divInputGroup.classList.add("input-group");
    let previousImage = document.querySelector(`#image_${numberOfFileInputs}`);
    if (previousImage.src === "") {
      return;
    }
    divInputGroup.appendChild(img);
    divInputGroup.appendChild(fileInput);
    addImageButton.insertAdjacentElement("beforebegin", divInputGroup);
    previewImages(fileInput);
  });
}
// Property form modal
const showPropertyFormButton = document.querySelectorAll(
  ".show-property-form span"
);
const propertyForm = document.querySelector(".property-form-backdrop");
const propertyFormModal = document.querySelector(".property-form-modal");
const closeModal = document.querySelector(".close-modal");
if (showPropertyFormButton && propertyForm) {
  showPropertyFormButton.forEach((showForm) =>
    showForm.addEventListener("click", function () {
      propertyForm.classList.toggle("d-none");
    })
  );
  propertyForm.addEventListener("click", function (e) {
    propertyForm.classList.add("d-none");
  });
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      propertyForm.classList.add("d-none");
    }
  });
  propertyFormModal.addEventListener("click", function (event) {
    event.stopPropagation();
  });
}
if (closeModal) {
  closeModal.addEventListener("click", function () {
    propertyForm.classList.add("d-none");
  });
}
// Auto complete address property form
async function autocomplete(address) {
  const customSelect = document.querySelector(".custom-select");
  const customSelectUl = document.querySelector(".custom-select ul");
  const cityInput = document.querySelector("#city");
  const zipcodeInput = document.querySelector("#zipcode");
  const streetInput = document.querySelector("#street");
  if (customSelectUl) {
    try {
      const response = await fetch(
        "https://api-adresse.data.gouv.fr/search/?q=" + address
      );
      const data = await response.json();
      if (data.features.length >= 5) {
        data.features.splice(5);
      }
      document.querySelectorAll(".custom-select ul li").forEach((li) => {
        li.remove();
      });
      customSelect.classList.remove("d-none");
      data.features.forEach((feature) => {
        const li = document.createElement("li");
        li.textContent = feature.properties.label;
        customSelectUl.appendChild(li);
        li.addEventListener("click", function () {
          cityInput.value = feature.properties.city;
          zipcodeInput.value = feature.properties.postcode;
          if (feature.properties.housenumber) {
            streetInput.value =
              feature.properties.housenumber + " " + feature.properties.street;
          } else {
            streetInput.value = feature.properties.street;
          }
          addressInput.value = li.textContent;
          customSelect.classList.add("d-none");
        });
      });
    } catch (error) {
      console.log(error);
    }
  }
}
// autocomplete address form event listener
const addressInput = document.querySelector("#address");
if (addressInput) {
  addressInput.addEventListener("input", function () {
    const address = addressInput.value;
    query = address.replace(/ /g, "+");
    autocomplete(query);
  });
}
