
function flightsRequest(origin, destination, departureDate, returnDate) {
    origin = encodeURIComponent(origin);
    destination = encodeURIComponent(destination);
    departureDate = encodeURIComponent(departureDate);
    returnDate = encodeURIComponent(returnDate);
    return fetch("/api/voli/getFlightOffers.php?origin=" + origin + "&destination=" + destination + "&departureDate=" + departureDate + "&returnDate=" + returnDate);
}

function bookFlightRequest(flight) {
    var formData = new FormData();
    formData.append('flight', JSON.stringify(flight));
    return fetch("/api/prenotazioni/bookFlight.php", {
        method: "POST",
        body: formData
    });
}

function prenotaVolo(event) {
    const id = event.currentTarget.dataset.flightId;
    const flight = flightsMap[id];
    console.log("flight: ", flight);
    bookFlightRequest(flight).then(onSuccess, onError).then(json => {
        console.log(json);
        alert("Prenotazione effettuata con successo!");
        window.location.href = "/app/prenotazioni/prenotazioni.php";
    }).catch(onErrorFlReq);
}

let flightsMap = {};

function createTicketElement(flights) {
    flightsMap = {};
    result.innerHTML = "";
    for (let flight of flights) {
        flightsMap[flight.id] = flight;
        const ticket = document.createElement("div");
        ticket.classList.add("ticket");
        const h2 = document.createElement("h2");
        h2.textContent = "Biglietto aereo";
        ticket.appendChild(h2);
        const h3 = document.createElement("h3");
        h3.textContent = "Tratte:";
        ticket.appendChild(h3);
        const tratteBox = document.createElement("div");
        tratteBox.classList.add("tratte-box");
        ticket.appendChild(tratteBox);
        const itinerari = ["andata", "ritorno"];
        for (let i = 0; i < itinerari.length; i++) {
            const itinerary = flight[itinerari[i]];
            const itineraryBox = document.createElement("div");
            const h2 = document.createElement("h2");
            h2.textContent = itinerari[i];
            itineraryBox.appendChild(h2);
            for (let i = 0; i < itinerary.tratte.length; i++) {
                const tratta = itinerary.tratte[i];
                const trattaBox = document.createElement("div");
                trattaBox.classList.add("tratta");
                const h4 = document.createElement("h4");
                h4.textContent = "Tratta " + (i + 1) + " " + tratta.partenza.iataCode + "-" + tratta.arrivo.iataCode;
                trattaBox.appendChild(h4);
                const partenza = document.createElement("div");
                partenza.classList.add("partenza");
                const h3partenza = document.createElement("h3");
                h3partenza.textContent = "Partenza";
                partenza.appendChild(h3partenza);
                let city = document.createElement("span");
                city.textContent = tratta.partenza.city + " (" + tratta.partenza.iataCode + ")";
                partenza.appendChild(city);
                const p2 = document.createElement("p");
                p2.textContent = "Orario della partenza: " + new Date(tratta.partenza.at).toLocaleString("it-IT");
                partenza.appendChild(p2);
                trattaBox.appendChild(partenza);
                const arrivo = document.createElement("div");
                arrivo.classList.add("arrivo");
                const h3arrivo = document.createElement("h3");
                h3arrivo.textContent = "Arrivo";
                arrivo.appendChild(h3arrivo);
                city = document.createElement("span");
                city.textContent = tratta.arrivo.city + " (" + tratta.arrivo.iataCode + ")";
                arrivo.appendChild(city);
                const p4 = document.createElement("p");
                p4.textContent = "Orario di arrivo: " + new Date(tratta.arrivo.at).toLocaleString("it-IT");
                arrivo.appendChild(p4);
                trattaBox.appendChild(arrivo);
                tratteBox.appendChild(trattaBox);
                itineraryBox.appendChild(trattaBox);
            }
            ticket.appendChild(itineraryBox);
        }
        const prezzo = document.createElement("div");
        prezzo.classList.add("price");
        prezzo.textContent = "Prezzo complessivo: " + flight.prezzo + " " + flight.valuta;
        ticket.appendChild(prezzo);
        const bookButton = document.createElement("button");
        bookButton.textContent = "Prenota";
        bookButton.dataset.flightId = flight.id;
        bookButton.addEventListener("click", prenotaVolo);
        ticket.appendChild(bookButton);
        result.appendChild(ticket);
    }
    show(result.parentElement);
}


function onErrorFlReq(errorResp) {
    errorResp.then(errors => {
        displayErrors(errors);
        hideLoader();
        form.classList.remove("hidden");
        window.scrollTo(0, document.body.scrollHeight);
    });
}

function search(event) {
    event.preventDefault();
    const formData = new FormData(form);

    const origin = formData.get("origin");
    const destination = formData.get("destination");
    const partenzaDate = formData.get("partenzaDate");
    const returnDate = formData.get("returnDate");

    hideMessages();
    showLoader();
    flightsRequest(origin, destination, partenzaDate, returnDate).then(onSuccess, onError).then(json => {
        console.log(json);
        let flights = json;
        // we create the ticket element
        createTicketElement(flights);
        hide(form);
        show(backButton);
        hideLoader();
    }).catch(onErrorFlReq);

}

function back(event) {
    form.reset();
    show(form);
    hide(backButton);
    hide(result);
}


const form = document.querySelector("#search-form");
form.addEventListener("submit", e => e.preventDefault());
const searchButton = document.querySelector("#search-button");
searchButton.addEventListener("click", search);
const backButton = document.querySelector("#back-button");
backButton.addEventListener("click", back);
const result = document.querySelector("#result .result-content");