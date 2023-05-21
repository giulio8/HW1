
function flightsRequest(origin, destination, departureDate, returnDate) {
    origin = encodeURIComponent(origin);
    destination = encodeURIComponent(destination);
    departureDate = encodeURIComponent(departureDate);
    returnDate = encodeURIComponent(returnDate);
    return fetch("/api/voli/getFlightOffers.php?origin=" + origin + "&destination=" + destination + "&departureDate=" + departureDate + "&returnDate=" + returnDate);
}

function bookFlightRequest(flight) {
    return fetch("/api/voli/bookFlight.php", {
        method: "POST",
        body: JSON.stringify(flight)
    });
}

function prenotaVolo(event) {
    const id = event.target.dataset.id;
    const flight = flightsMap[id];
    console.log(flight);
    bookFlightRequest(flight).then(onSuccess, onError).then(json => {
        console.log(json);
        alert("Prenotazione effettuata con successo!");
    });
}

let flightsMap = {};

function createTicketElement(flights) {
    flightsMap = {};
    const result = document.querySelector("#result");
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
                city = document.createElement("span");
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
                const city = document.createElement("span");
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
    result.classList.remove("hidden");
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
    const form = document.querySelector("#search-form");
    const formData = new FormData(form);

    const origin = formData.get("origin");
    const destination = formData.get("destination");
    const partenzaDate = formData.get("partenzaDate");
    const returnDate = formData.get("returnDate");

    showLoader();
    flightsRequest(origin, destination, partenzaDate, returnDate).then(onSuccess, onError).then(json => {
        console.log(json);
        let flights = json;
        // we create the ticket element
        createTicketElement(flights);
        hideLoader();
    }).catch(onErrorFlReq);

}

const form = document.querySelector("#search-form");
form.addEventListener("submit", e => e.preventDefault());
const button = document.querySelector("#search-button");
button.addEventListener("click", search);