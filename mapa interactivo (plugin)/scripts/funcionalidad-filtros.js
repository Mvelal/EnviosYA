/*
*   Nombre: funcionalidad-filtros.js
*   Uso: Funcionalidad para manejar los filtros del mapa
*/



/**
 * Espera a que el DOM esté completamente cargado y luego agrega un listener al formulario de filtros.
 *
 * Al enviar el formulario, previene el comportamiento por defecto, extrae los datos,
 * los envía mediante una función AJAX.
 *
 * @return void
 */

document.addEventListener('DOMContentLoaded', function () {
  
  const filtrosForm = document.getElementById('filtros-form');

  if (filtrosForm) {
    
    filtrosForm.addEventListener('submit', function (event) {
      
      event.preventDefault();

      const formData = new FormData(filtrosForm);

      filtrarLugaresAJAX(formData);
    });
  }
});



/**
 * Realiza una petición AJAX para filtrar lugares según los datos del formulario.
 *
 * Utiliza una función genérica `enviarAjax` para hacer la solicitud al servidor
 * con la acción 'filtrar_lugares'. Si se reciben resultados, se actualiza el mapa
 * y se renderizan las tarjetas correspondientes. Si no hay resultados, se muestra una alerta.
 *
 * @param {FormData} formData - Datos enviados desde el formulario de filtros.
 * @return {void}
 */

function filtrarLugaresAJAX(formData) {
  
  enviarAjax('filtrar_lugares', formData, function(data) {

    if (data && data.length > 0) {
      cargarUbicacionesEnMapa(data);
      renderTarjetasLugares(data);
    }else{
      alert("No se encontraron resultados para esos filtros");
    }
  });

} 



/**
 * Renderiza tarjetas HTML para la lista de lugares dentro del contenedor eco-aventura-card.
 *
 * Esta función limpia el contenido previo del contenedor y genera nuevas tarjetas
 * con la información de cada lugar, incluyendo imagen, título, precio, descripción
 * y un botón para mostrar más información.
 *
 * @param {Array} lugares - Lista de objetos con los datos de cada lugar (img, title, precio, descripcion).
 * @return {void}
 */

function renderTarjetasLugares(lugares) {
    const contenedor = document.getElementById('contenedor-tarjetas');
    contenedor.innerHTML = ''; // Limpiar resultados anteriores

    if (!lugares || lugares.length === 0) {
        contenedor.innerHTML = '<p>No se encontraron lugares.</p>';
        return;
    }

    lugares.forEach(lugar => {
        const card = document.createElement('div');
        card.className = 'eco-aventura-card';
        card.innerHTML = `
            <div class="eco-aventura-card__imagen-contenedor">
                ${lugar.img}
            </div>
            <div class="eco-aventura-card__texto-contenedor">
                <div class="eco-aventura-card__header">
                    <h2 class="eco-aventura-card__titulo">${lugar.title}</h2>
                    <span class="eco-aventura-card__precio">Precio: $${lugar.precio}</span>
                </div>
                <p class="eco-aventura-card__descripcion">${lugar.descripcion}</p>
                <button id="openModalBtn" class="openModalBtn eco-aventura-card__boton-info">Dame más info</button>
            </div>
        `;
        contenedor.appendChild(card);
    });
}



/**
 * Muestra el valor seleccionado del presupuesto en pantalla.
 *
 * Si el valor es 0, se interpreta como "Gratis". El resultado se inserta
 * en un elemento del DOM con el ID 'valorPresupuesto'.
 *
 * @param {number|string} valor - Valor del presupuesto seleccionado (puede ser número o texto).
 * @return {void}
 */

function mostrarValor(valor) {
  if (valor == 0){
    valor = 'Gratis';
  }
  document.getElementById("valorPresupuesto").innerText = valor;
}