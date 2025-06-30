<!--
 * Módulo de Mapa Interactivo con Filtros.
 *
 * Esta sección contiene:
 *
 * 1. Formulario de filtros:
 *    - Región/Ciudad: Carga dinámica de términos jerárquicos de la taxonomía "region" (países y ciudades).
 *    - Presupuesto: Rango de precios dinámico basado en los metadatos de los posts tipo "lugares".
 *    - Tipo de lugar: Selector basado en la taxonomía "tipo_lugar".
 *
 * 2. Contenedor del mapa:
 *    - `#mapa-js-container`: Mapa interactivo generado con Leaflet.
 *
 * 3. Contenedor de resultados:
 *    - `#contenedor-tarjetas`: Renderiza tarjetas HTML con la información de los lugares encontrados.
 *
 * Al enviar el formulario, se dispara una búsqueda AJAX que actualiza el mapa y las tarjetas,
 * sin recargar la página.
-->

<div class="mapa-interactivo">
  <div class="filtros">
    <form action="" id="filtros-form">
        <ul class="filtros__lista">
            <li class="filtros__item">
                <label>Donde te encuentras</label>

            <select id="regiones-ciudades" name="region_ciudad">
                <option value="">Selecciona una opción</option>
                <?php
                // Traer toda la taxonimia region y ponerla en el select
                $paises = get_terms(array(
                    'taxonomy'   => 'region',
                    'parent'     => 0,
                    'hide_empty' => false, 
                ));

                if (!empty($paises) && !is_wp_error($paises)) {
                    foreach ($paises as $pais) {
                        echo '<optgroup label="' . esc_attr($pais->name) . '">';

                        $ciudades = get_terms(array(
                            'taxonomy'   => 'region',
                            'parent'     => $pais->term_id, 
                            'hide_empty' => false,
                        ));

                        if (!empty($ciudades) && !is_wp_error($ciudades)) {
                            foreach ($ciudades as $ciudad) {
                                $option_value = $pais->slug . '-' . $ciudad->slug;
                                echo '<option value="' . esc_attr($option_value) . '">' . esc_html($ciudad->name) . '</option>';
                            }
                        }

                        echo '</optgroup>';
                    }
                }
                ?>
                </select>

            </li>

            <li class="filtros__item">
                <?php
                    // Obtener todos los precios de los posts del tipo "lugar"
                    $args = array(
                        'post_type'      => 'lugares',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'fields'         => 'ids',
                    );
                    $lugar_ids = get_posts($args);
                    $precios = [];

                    foreach ($lugar_ids as $id) {
                        $precio = get_post_meta($id, '_precio', true); 
                        if (is_numeric($precio)) {
                            $precios[] = floatval($precio);
                        }

                    }

                    $precio_maximo = !empty($precios) ? max($precios) : 100; 
                ?>

                <label for="presupuesto">¿Que presupuesto tienes?</label>
                <span id="valorPresupuesto">Gratis</span>
                <input type="range" id="presupuesto" value="0" name="presupuesto" min="0" max="<?php echo esc_attr($precio_maximo); ?>" oninput="mostrarValor(this.value)" step="1000">
  

            </li>

            <li class="filtros__item">
                <label>¿Algún plan en especifico?</label>

                <select id="tipo-lugar" name="tipo_lugar">
                    <option value="">Selecciona un tipo</option>
                    <?php
                    // Traer toda la taxonomia de Tipo_lugar y colocarla en el option
                    $tipos = get_terms(array(
                        'taxonomy'   => 'tipo_lugar',
                        'hide_empty' => false,
                    ));

                    if (!empty($tipos) && !is_wp_error($tipos)) {
                        foreach ($tipos as $tipo) {
                            echo '<option value="' . esc_attr($tipo->slug) . '">' . esc_html($tipo->name) . '</option>';
                        }
                    }
                    ?>
                </select>
            </li>
        </ul>

          <button class="filtros__submit" type="submit">Buscar</button>

    </form>
  </div>

  <!-- Contenedor del mapa -->
  <div id="mapa-js-container" class="mapa-interactivo__contenedor"></div>


  <!-- Contenedor de las tarjetas -->
  <div id="contenedor-tarjetas" class="contenedor-tarjetas"></div>


</div>