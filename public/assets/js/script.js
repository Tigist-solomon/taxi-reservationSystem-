

const DESTINATIONS = {
    "Piassa":    { coords: [9.0380, 38.7469] },  // Piassa / Arada
    "Bole":      { coords: [8.9952, 38.7879] },  // Bole Road 
    "Megenagna": { coords: [9.0268, 38.8002] },  // Megenagna roundabout
    "Kazanchis": { coords: [9.0096, 38.7640] },  // Kazanchis
    "Sarbet":    { coords: [8.9942, 38.7526] },  // Sarbet
    "CMC":       { coords: [9.0499, 38.8083] }   // CMC / Goro area
};


const MEXICO = [9.0109, 38.7453];

// ── MAP INIT ──
const map = L.map('map').setView(MEXICO, 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 18
}).addTo(map);

// Custom gold icon for pickup
const goldIcon = L.divIcon({
    html: '<div style="width:14px;height:14px;background:#C49A2A;border:2.5px solid #fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.35);"></div>',
    className: '',
    iconSize: [14, 14],
    iconAnchor: [7, 7]
});
const destIcon = L.divIcon({
    html: '<div style="width:14px;height:14px;background:#1A1714;border:2.5px solid #C49A2A;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.35);"></div>',
    className: '',
    iconSize: [14, 14],
    iconAnchor: [7, 7]
});

// Mexico Square marker
L.marker(MEXICO, { icon: goldIcon })
    .addTo(map)
    .bindPopup("<b>Mexico Square</b><br>📍 Pickup point")
    .openPopup();

let routeLayer = null;
let destMarker = null;

// SHOW ROUTE 
async function showRoute(destName) {
    const dest = DESTINATIONS[destName];
    if (!dest) return;
    const destCoords = dest.coords;

    // Remove old layers
    if (routeLayer) { map.removeLayer(routeLayer); routeLayer = null; }
    if (destMarker) { map.removeLayer(destMarker); destMarker = null; }

    // Add destination marker
    destMarker = L.marker(destCoords, { icon: destIcon })
        .addTo(map)
        .bindPopup("<b>" + destName + "</b><br>🏁 Destination")
        .openPopup();

    try {
        const apiKey = "eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6Ijg4YjlmNTk1ZTRkNzQyMWU5ZjRhMzkyOGZjMzdiMjU2IiwiaCI6Im11cm11cjY0In0=";
        const body = {
            coordinates: [
                [MEXICO[1], MEXICO[0]],
                [destCoords[1], destCoords[0]]
            ]
        };
        const res = await fetch(
            "https://api.openrouteservice.org/v2/directions/driving-car/geojson",
            {
                method: "POST",
                headers: { "Authorization": apiKey, "Content-Type": "application/json" },
                body: JSON.stringify(body)
            }
        );
        const data = await res.json();

        if (data.features && data.features.length > 0) {
            routeLayer = L.geoJSON(data, {
                style: { color: "#C49A2A", weight: 4.5, opacity: 0.9 }
            }).addTo(map);
            map.fitBounds(routeLayer.getBounds(), { padding: [50, 50] });

            const summary = data.features[0].properties.summary;
            const distKm  = (summary.distance / 1000).toFixed(1);
            const timeMin = Math.round(summary.duration / 60);
            showRouteInfo(distKm, timeMin, destName);
        } else {
            fallbackLine(destCoords, destName);
        }
    } catch(e) {
        console.warn("Route API failed, using straight line:", e);
        fallbackLine(destCoords, destName);
    }
}

function fallbackLine(destCoords, destName) {
    routeLayer = L.polyline([MEXICO, destCoords], {
        color: "#C49A2A", weight: 3.5, dashArray: "8 6", opacity: 0.85
    }).addTo(map);
    map.fitBounds(routeLayer.getBounds(), { padding: [50, 50] });
    // Estimate distance/time from straight-line (rough)
    const R = 6371;
    const dLat = (destCoords[0] - MEXICO[0]) * Math.PI / 180;
    const dLon = (destCoords[1] - MEXICO[1]) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 + Math.cos(MEXICO[0]*Math.PI/180)*Math.cos(destCoords[0]*Math.PI/180)*Math.sin(dLon/2)**2;
    const distKm  = (R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)) * 1.35).toFixed(1); // ×1.35 road factor
    const timeMin = Math.round(distKm / 25 * 60);
    showRouteInfo(distKm, timeMin, destName);
}

function showRouteInfo(distKm, timeMin, dest) {
    const el = document.getElementById("routeInfo");
    if (!el) return;
    el.style.display = "block";
    el.innerHTML =
        '<div class="route-stats">' +
        '<div class="route-stat">' +
            '<strong>' + distKm + ' km</strong>' +
            '<span>Distance</span>' +
        '</div>' +
        '<div class="route-stat-div"></div>' +
        '<div class="route-stat">' +
            '<strong>' + timeMin + ' min</strong>' +
            '<span>Est. Time</span>' +
        '</div>' +
        '<div class="route-stat-div"></div>' +
        '<div class="route-stat">' +
            '<strong>' + dest + '</strong>' +
            '<span>Destination</span>' +
        '</div>' +
        '</div>';
}

// Price and route
document.addEventListener("DOMContentLoaded", function () {
    const destSel = document.getElementById("destination");
    if (!destSel) return;

    destSel.addEventListener("change", function () {
        const pickup  = document.getElementById("pickup").value;
        const dropoff = this.value;
        if (!dropoff) return;

        //price
        const fd = new FormData();
        fd.append("pickup",  pickup);
        fd.append("dropoff", dropoff);
        fetch("/taxi_reservation/app/api/getPrice.php", { method: "POST", body: fd })
            .then(r => r.text())
            .then(price => {
                document.getElementById("price").value = price + " Birr";
                const disp = document.getElementById("price-display");
                if (disp) disp.textContent = price + " ETB";
            });

        // Draw route on map
        showRoute(dropoff);
    });
});
