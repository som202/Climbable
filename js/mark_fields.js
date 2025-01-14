let inputs = document.querySelectorAll(".field input");
inputs.forEach(i => {
    i.addEventListener("input", (e) => {
        let star = document.querySelector(`label[for='${e.target.id}']`).nextElementSibling;
        if (e.target.value !== "") {
            star.style.display = 'none';
        }
        else {
            star.style.display = 'inline';
        }
    });
});