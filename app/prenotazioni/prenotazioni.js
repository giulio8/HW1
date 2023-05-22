function prenotazioniRequest() {
    return fetch("/api/prenotazioni/getPrenotazioni.php");
}

function cancellaPrenotazioneRequest(id) {
    formData = new FormData();
    formData.append("id", id);
    return fetch("/api/prenotazioni/eliminaPrenotazione.php", {
        method: "POST",
        body: formData
    });
}

//let flightsMap = {};

function cancellaPrenotazione(event) {
    const id = event.currentTarget.dataset.flightId;
    cancellaPrenotazioneRequest(id).then(onSuccess, onError).then(json => {
        console.log(json);
        alert("Prenotazione cancellata con successo!");
        location.reload();
    }).catch(onErrorFlReq);
}

function createTicketElement(flights) {
    //flightsMap = {};
    const result = document.querySelector("#prenotazioni");
    for (let flight of flights) {
        //flightsMap[flight.id] = flight;
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
        const cancelButton = document.createElement("button");
        cancelButton.textContent = "Annulla prenotazione";
        cancelButton.dataset.flightId = flight.id;
        cancelButton.addEventListener("click", cancellaPrenotazione);
        ticket.appendChild(prezzo);
        ticket.appendChild(cancelButton);
        result.appendChild(ticket);
    }
    result.classList.remove("hidden");
}


function onErrorFlReq(errorResp) {
    console.log(errorResp);
    errorResp.then(errors => {
        displayErrors(errors);
        hideLoader();
    });
}

showLoader();
prenotazioniRequest().then(onSuccess, onError).then(json => {
    createTicketElement(json);
    hideLoader();
}).catch(onErrorFlReq);


