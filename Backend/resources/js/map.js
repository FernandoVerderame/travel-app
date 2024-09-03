function createIconSvg(color) {
    return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="36" height="36"><path fill="${color}" d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>`;
}


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
        const iconSvg = createIconSvg(location.color);
        const iconBlob = new Blob([iconSvg], { type: 'image/svg+xml' });
        const iconUrl = URL.createObjectURL(iconBlob);

        // Crea un marker per ogni location
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) },
            map: map,
            title: 'Clicca per vedere i dettagli',
            icon: {
                url: iconUrl
            }
        });

        marker.addListener('click', function () {
            const formattedDate = formatDate(location.date);

            const content = `
                <div class="custom-info-window">
                    <h3 class="fs-5 m-0">${location.title}</h3>
                    <p class="mb-1 mt-0">${formattedDate}</p>
                    <img src="http://[::1]:5173/public/storage/${location.image}" alt="Location Image">
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
