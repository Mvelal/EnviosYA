/*
*   Nombre: funcionalidad-ajax.js
*   Uso: Funcionalidad global para realizar los envios AJAX hacia la funcionalidad del Index.php
*/


/**
 * Envía datos mediante AJAX al servidor en WordPress.
 *
 * Esta función es llamada cuando se hace una solicitud AJAX con la acción 'enviar_correo'.
 * Se usa tanto para usuarios logueados como no logueados.
 *
 * @return void
 */

function enviarAjax(action, formData, callback) {
  formData.append('action', action);

  $.ajax({
    url: '/wp-admin/admin-ajax.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(data) {
      callback(data); // 🔁 Enviar el resultado a quien lo necesita
    },
    error: function(error) {
      console.error("Error:", error);
      callback(null);
    }
  });
}