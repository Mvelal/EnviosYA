/*
*   Nombre: funcionalidad-mapa.js
*   Uso: Funcionalidad del mapa y carga de contenido
*/



/**
 * Al cargar el DOM, verifica si hay ubicaciones disponibles
 * y, si existen, llama a `cargarUbicacionesEnMapa` para mostrarlas.
 */

document.addEventListener('DOMContentLoaded', () => {
    if (mapData?.locations?.length > 0) {
        cargarUbicacionesEnMapa(mapData.locations);
    }
});



/**
 * Inicializa el mapa Leaflet y carga marcadores con base en las ubicaciones recibidas.
 *
 * Al cargar el DOM, si existen datos en `mapData.locations`, se muestran en el mapa.
 * La función `cargarUbicacionesEnMapa` crea el mapa (si no existe), limpia marcadores anteriores,
 * agrega nuevos con popup informativo y ajusta la vista para incluir todos los puntos.
 *
 * @param {Array} locations - Arreglo de ubicaciones con propiedades: coords (lat,long), img, title, precio, descripcion, id.
 * @return {void}
 */

window.map = null;
window.markerGroup = null;

window.cargarUbicacionesEnMapa = function(locations) {
    if (!window.map) {

        window.map = L.map('mapa-js-container');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(window.map);

        window.markerGroup = L.layerGroup().addTo(window.map);
    } else {
        window.markerGroup.clearLayers();
    }

    // Guardamos las coordenadas para hacer fitBounds
    const bounds = [];

    locations.forEach(location => {
        const coords = location.coords.split(',').map(Number);
        bounds.push(coords); // <-- Añadir a los límites

        const marker = L.marker(coords);
        marker.bindPopup(`
            <article class="location-card">
                <div class="location-card__image">${location.img}</div>
                <div class="location-card__content">
                    <div class="location-card__header">
                        <h2 class="location-card__title">${location.title}</h2>
                        <span class="location-card__price">Precio: $${location.precio}</span>
                    </div>
                    <p class="location-card__description">${location.descripcion}</p>
                    <button id="openModalBtn" type="button" class="openModalBtn location-card__button" data-titulo="${location.id}">Dame más info</button>
                </div>
            </article>
        `);
        marker.addTo(window.markerGroup);
    });

    // Ajustar el mapa para que muestre todos los marcadores
    if (bounds.length > 0) {
        window.map.fitBounds(bounds, { padding: [50, 50] }); // padding opcional
    }
};
