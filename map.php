<?php
include('./includes/header.php');

$qry = "SELECT * FROM `dealer`";
$result = mysqli_query($conn, $qry);

$dealerLocations = [];
while ($vehicle = mysqli_fetch_assoc($result)) {
    $dealerLocations[] = [
        'lat' => $vehicle['dealer_latitude'],
        'lng' => $vehicle['dealer_longitude'],
        'name' => $vehicle['dealer_fname'] . ' ' . ($vehicle['dealer_lname'] ?? 'Dealer Location')
    ];
}
?>

<div class="bg-gray-300 p-4">
    <h1 class="text-2xl text-center underline font-bold mb-4">MAP VIEW</h1>
    <div class="p-3 rounded-xl bg-white">
        <div class="w-full h-screen rounded-md">
            <div id="map" class="w-full h-full"></div>
        </div>
    </div>
    <div class="flex items-center justify-center gap-3">
        <div class="flex items-center space-x-2 mt-4">
            <div class="w-5 h-5 rounded-full bg-red-500"></div>
            <p class="text-sm">Petrol Pump</p>
        </div>
        <div class="flex items-center space-x-2 mt-2">
            <div class="w-5 h-5 rounded-full bg-green-500"></div>
            <p class="text-sm">EV Charging Station</p>
        </div>
        <div class="flex items-center space-x-2 mt-2">
            <div class="w-5 h-5 rounded-full bg-blue-500"></div>
            <p class="text-sm">Our Dealer</p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
    const fuelStations = [{ latitude: 27.6139, longitude: 77.2090, name: 'Fuel Station 1' }, { latitude: 18.0760, longitude: 72.8777, name: 'Fuel Station 2' }, { latitude: 15.0827, longitude: 80.2707, name: 'Fuel Station 3' }];
    const evStations = [{ latitude: 28.6200, longitude: 77.2100, name: 'EV Station A' }, { latitude: 19.0800, longitude: 72.8800, name: 'EV Station B' }, { latitude: 13.0900, longitude: 80.2800, name: 'EV Station C' }];

    document.addEventListener('DOMContentLoaded', function() {
        const dealerLocations = <?php echo json_encode($dealerLocations); ?>;
        const defaultCoords = dealerLocations.length > 0 ? [dealerLocations[0].lat, dealerLocations[0].lng] : [20.5937, 78.9629];
        const map = L.map('map').setView(defaultCoords, dealerLocations.length > 0 ? 13 : 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' }).addTo(map);

        const blueIcon = new L.Icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] });
        const redIcon = new L.Icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] });
        const greenIcon = new L.Icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] });
        const purpleIcon = new L.Icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] });

        dealerLocations.forEach(dealer => { L.marker([dealer.lat, dealer.lng], { icon: blueIcon }).addTo(map).bindPopup(`<b>Dealer</b><br>${dealer.name}`); });
        fuelStations.forEach(station => { L.marker([station.latitude, station.longitude], { icon: redIcon }).addTo(map).bindPopup(`<b>Fuel Station</b><br>${station.name}`); });
        evStations.forEach(station => { L.marker([station.latitude, station.longitude], { icon: greenIcon }).addTo(map).bindPopup(`<b>EV Station</b><br>${station.name}`); });

        const allMarkers = [...dealerLocations.map(d => [d.lat, d.lng]), ...fuelStations.map(s => [s.latitude, s.longitude]), ...evStations.map(s => [s.latitude, s.longitude])];
        if (allMarkers.length > 0) { map.fitBounds(allMarkers, { padding: [50, 50] }); }

        // User Location and Radius
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                const userCircle = L.circle([userLat, userLng], { radius: 5000, color: 'purple', fillColor: 'purple', fillOpacity: 0.2 }).addTo(map); // 5000 meters radius
                L.marker([userLat, userLng],{icon: purpleIcon}).addTo(map).bindPopup("<b>You are here</b>").openPopup();

            }, () => {
                console.error("Geolocation is not available or permission denied.");
            });
        } else {
            console.error("Geolocation is not supported by this browser.");
        }
    });
</script>

<?php include('./includes/footer.php'); ?>