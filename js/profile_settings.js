let editButtons = document.querySelectorAll(".edit-button");
let cancelButtons = document.querySelectorAll(".cancel-button");

editButtons.forEach(button => {
    button.addEventListener("click", (e) => {
        let field = button.getAttribute("data-field");
        document.querySelector(`#${field}-display`).style.display = "none";
        document.querySelector(`#${field}-edit`).style.display = "block";
        e.target.style.display = "none";
    });
});

cancelButtons.forEach(button => {
    button.addEventListener("click", () => {
        let field = button.getAttribute("data-field");
        document.querySelector(`#${field}-edit`).style.display = "none";
        document.querySelector(`#${field}-display`).style.display = "inline";
        document.querySelector(`#${field}-edit-button`).style.display = "inline-block";
    });
});

let pwEditButton = document.querySelector("#password-edit-button");
let pwEditCancelButton = document.querySelector("#password-edit-cancel");
let pwEditForm = document.querySelector("#password-edit");

pwEditButton.addEventListener("click", (e) => {
    e.target.style.display = "none";
    pwEditForm.style.display = "block";
});
pwEditCancelButton.addEventListener("click", (e) => {
    pwEditForm.style.display = "none";
    pwEditButton.style.display = "inline-block";
});