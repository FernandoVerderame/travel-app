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
            const content = `
                <div class="custom-info-window">
                    <h3 class="fs-5">${location.title}</h3>
                    <img src="https://media.istockphoto.com/id/1336469257/it/foto/famoso-cartello-di-las-vegas-allingresso-della-citt%C3%A0-dettaglio-di-notte.jpg?s=612x612&w=0&k=20&c=1bnGBcd8j8E4FEM-3_jhCIqfbgwhU8SF1ywc-njvhVM=" alt="Location Image" style="width: 200px; height: auto;">
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
