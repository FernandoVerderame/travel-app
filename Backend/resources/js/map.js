function formatDate(dateString) {
    const date = new Date(dateString);

    const day = ('0' + date.getDate()).slice(-2);
    const month = ('0' + (date.getMonth() + 1)).slice(-2);
    const year = date.getFullYear();

    return `${day}-${month}-${year}`;
}

let map;

function initMap() {
    // Inizializza la mappa senza un centro fisso
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 2,
        center: { lat: 0, lng: 0 },
        mapTypeId: 'roadmap'
    });

    const infowindow = new google.maps.InfoWindow();

    // Aggiungi i marker alla mappa utilizzando le locations passate dal Blade
    locations.forEach(location => {
        // Crea un marker per ogni location
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) },
            map: map,
            title: 'Clicca per vedere i dettagli'
        });

        marker.addListener('click', function () {
            const formattedDate = formatDate(location.date);

            const content = `
                <div class="custom-info-window">
                    <h3 class="fs-5 m-0">${location.title}</h3>
                    <p class="mb-1 mt-0">${formattedDate}</p>
                    <img src="https://placehold.co/160x120" alt="Location Image">
                </div>
            `;
            infowindow.setContent(content);
            infowindow.open(map, marker);
        });
    });

    // Adatta automaticamente lo zoom per includere tutti i marker
    let bounds = new google.maps.LatLngBounds();
    locations.forEach(location => {
        bounds.extend(new google.maps.LatLng(location.latitude, location.longitude));

    });
    map.fitBounds(bounds);
}

window.initMap = initMap;
