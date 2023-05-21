function displayErrors(errors) {
    var errorDiv = document.querySelector("#error");
    for (let i in errors) {
        let div = document.createElement("div");
        div.textContent = errors[i];
        errorDiv.appendChild(div);

        errorDiv.classList.remove("hidden");
    }
}