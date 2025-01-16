let modal = document.querySelector("#overlay");

const openModal = function() {
    modal.style.display = "flex";
}
const closeModal = function() {
    modal.style.display = "none";
}
let postBtn = document.querySelector("#action-buttons button:first-child");
postBtn.addEventListener("click", openModal);
let closeBtn = document.querySelector("#post-form #btn-close");
closeBtn.addEventListener("click", closeModal);
modal.addEventListener("click", (e) => {
    if (e.target.id === "overlay") {
        closeModal();
    }
});

let errDiv = document.querySelector("#form-error");
document.querySelector("#post-form").addEventListener("submit", (e) => {
    let fileInput = document.querySelector("#video");
    const maxSize = 20971520; //20 MB
    
    if (fileInput.files[0].size > maxSize) {
      errDiv.innerHTML = "<span>File is too large. Maximum allowed size is 20 MB</span>";
      e.preventDefault();
    }
});
document.querySelector("#video").addEventListener("change", (e) => {
    const maxSize = 20971520; //20 MB
    
    if (e.target.files[0].size <= maxSize) {
      errDiv.innerHTML = "";
    }
});
