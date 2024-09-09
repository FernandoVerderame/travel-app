// Funzione per creare un'icona SVG personalizzata con un colore specifico
function createIconSvg(color) {
    // Restituisce una stringa SVG con il colore passato come parametro
    return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="36" height="36"><path fill="${color}" d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>`;
}

// Funzione per formattare una data in formato 'gg-mm-aaaa'
function formatDate(dateString) {
    // Crea un oggetto Date a partire dalla stringa passata
    const date = new Date(dateString);

    // Estrae il giorno, mese e anno
    const day = ('0' + date.getDate()).slice(-2); // Aggiunge lo zero davanti se necessario
    const month = ('0' + (date.getMonth() + 1)).slice(-2); // I mesi partono da 0
    const year = date.getFullYear();

    // Restituisce la data formattata
    return `${day}-${month}-${year}`;
}

let map; // Variabile globale per la mappa

// Funzione per inizializzare la mappa di Google
function initMap() {
    // Inizializza la mappa senza un centro fisso, con zoom predefinito
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 2, // Imposta lo zoom iniziale
        center: { lat: 0, lng: 0 }, // Centro iniziale della mappa
        mapTypeId: 'roadmap' // Tipo di mappa (stradale)
    });

    const infowindow = new google.maps.InfoWindow(); // Finestra informativa per i marker

    // Cicla attraverso le locations (presumibilmente un array passato dal backend)
    locations.forEach(location => {
        // Crea un'icona SVG per ogni location con il colore associato
        const iconSvg = createIconSvg(location.color);
        const iconBlob = new Blob([iconSvg], { type: 'image/svg+xml' }); // Crea un blob dell'SVG
        const iconUrl = URL.createObjectURL(iconBlob); // Crea un URL temporaneo per l'icona

        // Crea un marker per ogni location con posizione e icona personalizzata
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) }, // Posizione del marker
            map: map, // Aggiunge il marker alla mappa
            title: 'Clicca per vedere i dettagli', // Testo che appare al passaggio del mouse
            icon: {
                url: iconUrl // Imposta l'icona personalizzata
            }
        });

        // Aggiungi un listener per il click sul marker
        marker.addListener('click', function () {
            const formattedDate = formatDate(location.date); // Formatta la data della location

            // Contenuto della finestra informativa (HTML personalizzato)
            const content = `
                <div class="custom-info-window">
                    <h3 class="fs-5 m-0">${location.title}</h3>
                    <p class="mb-1 mt-0">${formattedDate}</p>
                    <figure class="location-thumb">
                    <img src="http://[::1]:5173/public/storage/${location.image}" alt="Location Image">
                    </figure>
                    <p class="mb-0">${location.notes}</p>
                </div>
            `;
            infowindow.setContent(content); // Imposta il contenuto della finestra informativa
            infowindow.open(map, marker); // Mostra la finestra informativa sul marker
        });
    });

    // Adatta lo zoom della mappa per includere tutti i marker presenti
    let bounds = new google.maps.LatLngBounds();
    locations.forEach(location => {
        // Espande i limiti della mappa includendo le coordinate della location
        bounds.extend(new google.maps.LatLng(location.latitude, location.longitude));
    });
    map.fitBounds(bounds); // Applica i limiti per adattare la mappa
}

// Assegna la funzione initMap alla proprietà globale window (per Google Maps API callback)
window.initMap = initMap;
