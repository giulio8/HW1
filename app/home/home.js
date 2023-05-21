function setHeaders(a_t) {
    let headers = {
        "Authorization": "Bearer " + a_t
    };
    return headers;
}
// image API /// ------------------------------

function albumRequest() {
    return fetch("/api/imgur/album.php");
}
// --------------------------------------------
// coordinates api

function requestCoordinates(queryString) {
    return fetch("/api/openweather/coordinates.php?q=" + encodeURIComponent(queryString));
}
// --------------------------------------------
// flights api

function airportRequest(lat, lon) {
    return fetch("/api/flights/airport_by_coordinates.php?lat=" + lat + "&lon=" + lon);
}

function flightsRequest(origin, destination, departureDate, returnDate) {
    return fetch("/api/flights/flight.php?origin=" + origin + "&destination=" + destination + "&departureDate=" + departureDate + "&returnDate=" + returnDate);
}
// --------------------------------------------

function onJson(json) {
    console.log(json)
}

function showLoader() {
    const loader = document.querySelector("#loader");
    loader.classList.remove("hidden");
}

function hideLoader() {
    const loader = document.querySelector("#loader");
    loader.classList.add("hidden");
}

function showModal() {
    const modal = document.querySelector("#modal");
    modal.classList.remove("hidden");
}

function hideModal() {
    const modal = document.querySelector("#modal");
    modal.classList.add("hidden");
}

function createTicketElement(flight) {
    const ticket = document.createElement("div");
    ticket.classList.add("ticket");
    const h2 = document.createElement("h2");
    h2.textContent = "Biglietto aereo";
    ticket.appendChild(h2);
    const h3 = document.createElement("h3");
    h3.textContent = "Tratte:";
    ticket.appendChild(h3);
    const segmentsBox = document.createElement("div");
    segmentsBox.classList.add("segments-box");
    ticket.appendChild(segmentsBox);
    itinerary = flight.itineraries[0];

    for (let i = 0; i < itinerary.segments.length; i++) {
        const segment = itinerary.segments[i];
        const segmentBox = document.createElement("div");
        segmentBox.classList.add("segment");
        const h4 = document.createElement("h4");
        h4.textContent = "Tratta " + (i + 1) + " " + segment.departure.iataCode + "-" + segment.arrival.iataCode;
        segmentBox.appendChild(h4);
        const departure = document.createElement("div");
        departure.classList.add("departure");
        const h3Departure = document.createElement("h3");
        h3Departure.textContent = "Partenza";
        departure.appendChild(h3Departure);
        city = document.createElement("span");
        city.textContent = segment.departure.city + " (" + segment.departure.iataCode + ")";
        departure.appendChild(city);
        const p2 = document.createElement("p");
        p2.textContent = "Orario della partenza: " + new Date(segment.departure.at).toLocaleString("it-IT");
        departure.appendChild(p2);
        segmentBox.appendChild(departure);
        const arrival = document.createElement("div");
        arrival.classList.add("arrival");
        const h3Arrival = document.createElement("h3");
        h3Arrival.textContent = "Arrivo";
        arrival.appendChild(h3Arrival);
        city = document.createElement("span");
        city.textContent = segment.arrival.city + " (" + segment.arrival.iataCode + ")";
        arrival.appendChild(city);
        const p4 = document.createElement("p");
        p4.textContent = "Orario di arrivo: " + new Date(segment.arrival.at).toLocaleString("it-IT");
        arrival.appendChild(p4);
        segmentBox.appendChild(arrival);
        segmentsBox.appendChild(segmentBox);
    }
    const price = document.createElement("div");
    price.classList.add("price");
    price.textContent = "Prezzo complessivo: " + flight.price.total + " " + flight.price.currency;
    ticket.appendChild(price);
    const modal = document.querySelector("#modal .modal-content");
    modal.appendChild(ticket);

}


function getFlights(origin, destination, departureDate, returnDate) {
    // we request the flights
    flightsRequest(origin, destination, departureDate, returnDate).then(onSuccess, onError).then(json => {
        console.log(json);
        let flight = json;
        // we create the ticket element
        createTicketElement(flight);
        hideLoader();
        showModal();
    }).catch(onErrorFlReq);
}

function onErrorFlReq(err) {
    console.log(err);
    let error = document.querySelector("#error");
    error.classList.remove("hidden");
    hideLoader();
    window.scrollTo(0, document.body.scrollHeight);
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

function onAlbumReturned(json) {
    console.log(json)
    const list = document.querySelector("#gallery");
    for (const i in json.data.images) {
        const img = json.data.images[i];
        let imgEl = document.createElement("img");
        imgEl.src = img.link
        imgEl.classList.add("gallery-image");
        imgEl.dataset.title = img.title;
        list.appendChild(imgEl);
    }
    list.addEventListener("click", onImageClick);
}

function onSuccess(resp) {
    console.log(resp.status);
    if (resp.ok === false && resp.status !== 429) {
        console.log("Problem with the request");
        throw new Error("Problem with the request");
    }
    return resp.json();
}

function onError(error) {
    console.log('Error: ' + error);
}


function addImageToAlbum(imageHash) {
    let formdata = new FormData();
    formdata.append("ids[]", imageHash);
    formdata.append("album", imgurData.albumHash);
    console.log("funzoine")
    return addImageRequest(formdata);
}

function postImage(event) {
    let form = document.forms["postImage"];
    let formdata = new FormData(form);
    formdata.append("type", "file");

    // we show the loading animation and hide all the form, then scroll to bottom
    showLoader();
    form.classList.add("hidden");


    // We must show the error message if the image is not uploaded
    // and hide the loading animation
    function onErrorImReq() {
        let error = document.querySelector("#error");
        error.classList.remove("hidden");
        hideLoader();
        form.classList.remove("hidden");
        window.scrollTo(0, document.body.scrollHeight);
    }

    postImageRequest(formdata).then(onSuccess, onErrorImReq).then(json => {
        console.log("Immagine caricata correttamente");
        addImageToAlbum(json.data.id).then(onSuccess, onError).then(() => {
            console.log("Immagine aggiunta correttamente");
            location.reload();
        });
    }).catch(onErrorImReq);

}

albumRequest().then(onSuccess, onError).then(onAlbumReturned);

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