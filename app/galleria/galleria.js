// image API /// ------------------------------

function destRequest() {
    return fetch("/api/destinazioni/getDestinazioni.php");
}

function postImageRequest(formData) {
    return fetch("/api/destinazioni/caricaDestinazione.php", {
        method: "POST",
        body: formData
    });
}

function deleteImageRequest(formTitolo) {
    return fetch("/api/destinazioni/eliminaDestinazione.php", {
        method: "POST",
        body: formTitolo
    });
}

// --------------------------------------------
// coordinates api

function requestCoordinates(queryString) {
    return fetch("/api/openweather/coordinates.php?q=" + encodeURIComponent(queryString));
}
// --------------------------------------------
// flights api

function airportRequest(lat, lon) {
    return fetch("/api/voli/airport_by_coordinates.php?lat=" + lat + "&lon=" + lon);
}

// --------------------------------------------

function showModal() {
    const modal = document.querySelector("#modal");
    modal.classList.remove("hidden");
}

function hideModal() {
    const modal = document.querySelector("#modal");
    modal.classList.add("hidden");
}


function onImageClick(event) {
    showLoader();

    // we requst the coordinates of the place
    let place = event.target.dataset.title;
    requestCoordinates(place).then(onSuccess, onError).then(json => {
        console.log(json);
        let lat = json.lat;
        let lon = json.lon;

        airportRequest(lat, lon).then(onSuccess, onError).then(json => {
            console.log(json);
            let airport = json.iataCode;
            console.log(airport);
            // get tomorrow's date
            let tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            day_after = new Date();
            day_after.setDate(day_after.getDate() + 2);
            // we request the flights for the day after today
            getFlights("CTA", airport, tomorrow.toISOString().split("T")[0], day_after.toISOString().split("T")[0]);
        }).catch(onErrorFlReq);
    });
}

function onEliminaDestinazione(event) {
    const titolo = event.currentTarget.dataset.titolo;
    formTitolo = new FormData();
    formTitolo.append("titolo", titolo);
    deleteImageRequest(formTitolo).then(onSuccess, onError).then(json => {
        console.log(json);
        window.location.reload();
    });
}

function onTrovaVoliDestinazione(event) {
    const titolo_destinazione = event.currentTarget.dataset.titolo;
    console.log(titolo_destinazione);
    // We navigate to the flight offers page
    window.location.href = "/app/offerte/offerte.php?luogo=" + titolo_destinazione;
}


function onAlbumReturned(json) {
    console.log(json)
    const list = document.querySelector("#gallery");
    for (const i in json.data) {
        const img = json.data[i];
        let imgEl = document.createElement("img");
        imgEl.src = "/app/media/" + img.immagine;
        imgEl.classList.add("gallery-image");
        imgEl.dataset.title = img.titolo;
        elimina = document.createElement("button");
        elimina.textContent = "Elimina";
        elimina.classList.add("elimina");
        elimina.dataset.titolo = img.titolo;
        elimina.addEventListener("click", onEliminaDestinazione);
        imgEl.dataset.title = img.titolo;
        trovaVoli = document.createElement("button");
        trovaVoli.textContent = "trova voli";
        trovaVoli.classList.add("trova-voli");
        trovaVoli.dataset.titolo = img.titolo;
        trovaVoli.addEventListener("click", onTrovaVoliDestinazione);
        list.appendChild(imgEl);
        list.appendChild(elimina);
        list.appendChild(trovaVoli);
    }
    list.addEventListener("click", onImageClick);
}


function postImage(event) {
    let form = document.forms["postImage"];
    let formdata = new FormData(form);

    // we show the loading animation and hide all the form, then scroll to bottom
    showLoader();
    form.classList.add("hidden");


    // show the error message if the image is not uploaded
    // and hide the loading animation
    function onErrorImReq(errorResp) {
        errorResp.then(errors => {
            displayErrors(errors);
            hideLoader();
            form.classList.remove("hidden");
            window.scrollTo(0, document.body.scrollHeight);
        });
    }

    postImageRequest(formdata).then(onSuccess, onError).then(json => {
        console.log(json);
        location.reload();
    }).catch(onErrorImReq);

}

destRequest().then(onSuccess, onError).then(onAlbumReturned);

postButton = document.querySelector("#post-button");

// prevent button from submitting form
postButton.addEventListener("click", (event) => event.preventDefault());

postButton.addEventListener("mouseup", postImage);

closeModal = document.querySelector(".modal-content .close");
closeModal.addEventListener("click", () => {
    hideModal();
    let modal = document.querySelector("#modal .modal-content");
    modal.innerHTML = '<span class="close">&times;</span>';
});