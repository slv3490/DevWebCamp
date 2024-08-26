if(document.querySelector("#mapa")) {

    const lat = -33.686988405663236;
    const lng = -65.49541042574899;
    const zoom = 16;

    const map = L.map('mapa').setView([lat, lng], zoom);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup(`
            <h2 class="mapa__heading">Parque La Pedrera</h2>
            <p class="mapa__texto">Centro del parque La Pedrera</p>
        `)
        .openPopup();
}