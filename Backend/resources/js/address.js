/* RECUPERO ELEMENTI */
const proxyUrl = 'https://cors-anywhere.herokuapp.com/';

/* INPUT DI RICERCA */
const inputAddressSearch = document.getElementById('search-address');
/* UL PER LA LISTA DELLA VIA */
const suggestionAddress = document.getElementById('suggestions');
/* INPUT LATITUDINE */
const latInput = document.getElementById('latitude');
/* INPUT LOGITUDINE */
const lonInput = document.getElementById('longitude');

/* INPUT BOTTONE SALVA */
const saveButton = document.getElementById('save-btn');

/* RECUPER URL DAL SITO TOMTOM */
const baseUri = 'https://maps.googleapis.com/maps/api/geocode/json';


/* FUNZIONE A CUI PASSO COME PARAMENTRO IL VALORE DELL'INDIREZZO SCELTO DALL'UTENTE */
const searchPlace = addressTerm => {

    /* MODIFICO UL CON UN LOADER ANIMATO */
    suggestionAddress.innerHTML = '<li class="pe-none"><i class="fas fa-spinner fa-pulse p-3"></i></li>';

    /* AGGIUNGO LA CLASSE SHOW PER CONTROLLARE LA VISIBILITA' DELL'ELEMENTO HTML */
    suggestionAddress.classList.add('show');

    /* METTO IL CLEAR PRIMA, IN MODO DA ANNULLO IL SETTIMEOUT E MI ASSICURA CHE LE CHIAMATE PRECEDENTI VENGONO CANCELLATE */
    clearTimeout(timeoutId);

    /* CREO UN SETTIMEOUT CON UN RITARDO DI 400MILLISECONDI CHE RICHIAMA LA FUNZIONE PER GESTIRE API DEL TOMTOM PASSANDO COME PARAMENTRO L'INDRIZZO SCELTO DALL'UTENTE */
    timeoutId = setTimeout(() => {
        fetchApi(addressTerm);
    }, 400);
}


/* FUNZIONE PER GESTIRE API DEL TOMTOM E GLI PASSO INDIREZZO SCELTO DALL'UTENTE */
const fetchApi = query => {

    /* SE NON E' STATO MESSO L'INDIRIZZO */
    if (!query) {

        /* METTO A NULLI I VALORI DELLA LATITUDINE E LONGITUDINE */
        latInput.value = null;
        lonInput.value = null;

        /* RIMUOVO LA CLASSE SHOW PER CONTROLLARE LA VISIBILITA' DELL'ELEMENTO HTML */
        suggestionAddress.classList.remove('show');

        /* CANCELLO IL CONTENUTO NEL HTML */
        suggestionAddress.innerHTML = '';

        /* RESTITUISCO (BLOCCO) */
        return;
    }

    /* CHIAMATA API PASSANDO URL + INDIRIZZO + TRASFORMO LA RISPOSTA IN JSON */
    axios.get(proxyUrl, baseUri, {
        params: {
            address: query,
            key: import.meta.env.VITE_GOOGLE_MAPS_API_KEY // API KEY
        }
    }).then(res => {

        /* ESTRAGGO DA RES.DATA IL RESULTS */
        const { results } = res.data;

        /* SE LA LUNGEZZA E' VUOTA RESTITUISCO (BLOCCO) */
        if (!results.length) return;

        /* CANCELLO IL CONTENUTO NEL HTML (LOADER) */
        suggestionAddress.innerHTML = '';

        /* CICLO SULL'ARRAY ESTRATTO PER RECUPERARE OGNI SINGOLO ELEMENTO */
        results.forEach(result => {

            /* RECUPERO VIA,LATITUDINE,LONGITUDINE */
            const place = {
                address: result.formatted_address,
                lat: result.geometry.location.lat,
                lon: result.geometry.location.lng
            };


            /* AGGIUNGO GLI ELEMENTI ESTRAPOLATI IN PAGINA HTML */
            suggestionAddress.innerHTML += `<li class="suggestions-item py-2" data-lat="${place.lat}" data-lon="${place.lon}">${place.address}</li>`;

        });

        /* EVENTUALI ERRORI */
    }).catch(err => {
        console.log(err);
        /* MODIFICO UL CON UN MESSAGGIO DI ERRORE */
        suggestionAddress.innerHTML = '<li class="text-danger pe-none p-3">Impossibile contattare il server</li>';
    });
}

/* VARIBILE DA MANIPOLARE AL SETTIMEOUT */
let timeoutId = null;


/* ALL'INPUT DI RICERCA CREO EVENTO SU OGNI SINGOLO TASTO  */
inputAddressSearch.addEventListener('keyup', () => {

    /* METTO IN UNA COSTANTE IL VALORE INSERITO DALL'UTENTE ED ELIMINO EVENTUALI SPAZI */
    const addressTerm = inputAddressSearch.value.trim();

    /* PASSO ALLA FUNZIONE IL VALORE INSERITO DALL'UTENTE */
    searchPlace(addressTerm);
});


/* EVENTO AL CLICK SU UN ELEMENTO DELLA LISTA */
suggestionAddress.addEventListener('click', (event) => {

    /* RESTITUISCO LìELEMENTO SPECIFICO CHE A GENERATO IL CLICK */
    const suggestion = event.target;

    /* SE L'ELEMENTO CLICCATO NON CONTIENE LA CLASSE 'suggestions-item') BLOCCO TUTTO */
    if (!suggestion.classList.contains('suggestions-item')) return

    /* RIASSEGNO IL VALORE INSERITO DALL'UTENTE CON IL VALORE SELEZIONATO TRAMITE CLICK */
    inputAddressSearch.value = suggestion.innerText;
    latInput.value = suggestion.dataset.lat;
    lonInput.value = suggestion.dataset.lon;

    /* VUOTO LA LISTA DOP IL CLICK DELL'UTENTE */
    suggestionAddress.innerHTML = '';

});


/* EVENTO AL KEYUP DELLA MODIFICA SE IL CAMPO NON CORRISPONDE A QUELLI SUGGERITI */
inputAddressSearch.addEventListener('keyup', () => {

    /* RIMETTO I VALORI DI LAT E LON A NULLI */
    latInput.value = null;
    lonInput.value = null;
});

/* EVENTO AL SALVA CON ALERT DI UN INDIRIZZO NON VALIDO */
saveButton.addEventListener('click', () => {
    if (!latInput.value || !lonInput.value) {
        alert('Indirizzo non valido')
    }
})





















