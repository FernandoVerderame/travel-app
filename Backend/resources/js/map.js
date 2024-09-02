let map;

function initMap() {
    // Inizializza la mappa senza un centro fisso
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 2,
        center: { lat: 0, lng: 0 },
        mapTypeId: 'roadmap'
    });

    // Aggiungi i marker alla mappa utilizzando le locations passate dal Blade
    locations.forEach(location => {
        new google.maps.Marker({
            position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) },
            map: map
        });
    });

    // Opzionale: Adatta automaticamente lo zoom per includere tutti i marker
    let bounds = new google.maps.LatLngBounds();
    locations.forEach(location => {
        bounds.extend(new google.maps.LatLng(location.latitude, location.longitude));

    });
    map.fitBounds(bounds);
}

window.initMap = initMap;
