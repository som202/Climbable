let searchField = document.querySelector("#search-field");

window.onload = function() {
    searchField.value = "";
};

searchField.addEventListener("focus", () => {
    document.querySelector(".user-tip").style.display = "none";
}, {once: true});

let ol = document.querySelector("#results ol");

searchField.addEventListener("input", (e) => {
    let userInput = e.target.value;
    
    if (userInput.length < 3) {
        ol.innerHTML = "<li>Type at least 3 characters (minimal username length)</li>";
    }
    else if (userInput.length >= 3 && /^[a-zA-Z0-9]+$/.test(userInput)) {
        fetch("search_process.php", {   
            method: "POST",
            headers: {
                "Content-Type": "text/plain"
            },
            body: userInput
        })
        .then(response => response.json())
        .then(data => {
            ol.innerHTML = "";
            if (data.length === 0) {
                ol.innerHTML = "<li>No results found</li>";
            }
            else {
                ol.innerHTML = "<li>Found users:</li>";
                data.forEach(username => {
                    let li = document.createElement("li");
                    li.innerHTML = `<a href="${username}">${username}</a>`;
                    ol.appendChild(li);
                });
            }
        })
        .catch(err => console.error("Error: ", err));
    }
    else {
        ol.innerHTML = "<li>Special characters in usernames aren't allowed</li>";
    }
});