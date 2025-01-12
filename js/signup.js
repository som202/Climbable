function checkPasswordMatch() {
    let pw1 = document.querySelector("#password").value;
    let pw2 = document.querySelector("#password-confirm").value;
    let errDiv = document.querySelector("#form-error");
    if (pw1 === pw2) {
        errDiv.innerHTML = "";
        return true;
    }
    errDiv.innerHTML = "<span>Passwords don't match!</span>";
    return false;
}

document.querySelector("#signup-form").addEventListener("submit", (e) => {
    if (!checkPasswordMatch()) {
        e.preventDefault();
    }
});