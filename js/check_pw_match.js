function checkPasswordMatch() {
    let pw1 = document.querySelector("#password").value;
    let pw2 = document.querySelector("#password-confirm").value;
    let errDiv = document.querySelector("#form-error");
    if (pw1 === pw2) {
        errDiv.innerHTML = "";
        return true;
    }
    errDiv.innerHTML = "<span>Passwords didn't match!</span>";
    return false;
}

let form = document.querySelector("#signup-form") || document.querySelector("#password-edit");
form.addEventListener("submit", (e) => {
    if (!checkPasswordMatch()) {
        e.preventDefault();
    }
});