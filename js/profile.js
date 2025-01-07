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