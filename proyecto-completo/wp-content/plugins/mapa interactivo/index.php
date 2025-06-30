<?php
/**
* Plugin Name: mapa interactivo
* Description: Mapa interactivo con Custom Post Types
* Version: 0.1
* Author: Mvela
**/

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}



/**
 * Encola los estilos y scripts necesarios para el plugin, incluyendo Leaflet y scripts personalizados.
 * También localiza datos de ubicaciones para uso en JavaScript.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', 'mi_plugin_leaflet_assets');

function mi_plugin_leaflet_assets() {

    wp_enqueue_style(
        'mapa-interactivo-styles',
        plugins_url('css/styles.css', __FILE__)
    );

    wp_enqueue_style(
        'leaflet-css',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
    );

    wp_enqueue_script(
        'JQuery',
        'https://code.jquery.com/jquery-3.7.1.min.js',
        array(),
        null,
        true
    );

    wp_enqueue_script(
        'leaflet-js',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
        array(),
        null,
        true
    );

    wp_enqueue_script(
        'funcionalidad-ajax',
        plugin_dir_url(__FILE__) . 'scripts/funcionalidad-ajax.js',
        array('leaflet-js'),
        '1.0',
        true
    );

    wp_enqueue_script(
        'funcionalidad-filtros',
        plugin_dir_url(__FILE__) . 'scripts/funcionalidad-filtros.js',
        array('leaflet-js'),
        '1.0',
        true
    );

    wp_enqueue_script(
        'funcionalidad-mapa',
        plugin_dir_url(__FILE__) . 'scripts/funcionalidad-mapa.js',
        array('leaflet-js'),
        '1.0',
        true
    );

    wp_enqueue_script(
        'funcionalidad-formulario',
        plugin_dir_url(__FILE__) . 'scripts/funcionalidad-formulario.js',
        array('leaflet-js'),
        '1.0',
        true
    );
    
    $lugares = get_posts(array(
        'post_type' => 'lugares',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));

    $ubicaciones = obtener_datos_lugares($lugares);


    wp_localize_script('funcionalidad-mapa', 'mapData', array(
        'locations' => $ubicaciones
    ));

}



/**
 * Registra el Custom Post Type 'lugares' para mostrar ubicaciones en el mapa.
 *
 * @return void
 */
function cpt_lugares() {

    $labels = array(
        'name'               => 'Lugares',
        'singular_name'      => 'Lugar',
        'menu_name'          => 'Lugares',
        'name_admin_bar'     => 'Lugar',
        'add_new'            => 'Añadir nuevo',
        'add_new_item'       => 'Añadir nuevo lugar',
        'new_item'           => 'Nuevo lugar',
        'edit_item'          => 'Editar lugar',
        'view_item'          => 'Ver lugar',
        'all_items'          => 'Todos los lugares',
        'search_items'       => 'Buscar lugares',
        'not_found'          => 'No se encontraron lugares',
        'not_found_in_trash' => 'No hay lugares en la papelera'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'lugares'),
        'supports'           => array('title', 'editor', 'thumbnail','excerpt', 'custom-fields'),
        'menu_icon'          => 'dashicons-portfolio',
        'show_in_rest'       => true, 
    );

    register_post_type('lugares', $args);
}
add_action('init', 'cpt_lugares');



/**
 * Registra la taxonomía jerárquica 'region' (paises/ciudades) asociada a los lugares.
 *
 * @return void
 */
function taxonomia_regiones_lugar() {
    $labels = array(
        'name'              => 'Paises',
        'singular_name'     => 'Pais',
        'search_items'      => 'Buscar pais',
        'all_items'         => 'Todas las paises',
        'edit_item'         => 'Editar paises',
        'update_item'       => 'Actualizar paises',
        'add_new_item'      => 'Añadir nueva paises',
        'new_item_name'     => 'Nombre de la nueva paises',
        'menu_name'         => 'Paises',
    );

    $args = array(
        'hierarchical'      => true, 
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'region'),
    );

    register_taxonomy('region', array('lugares'), $args);
}

add_action('init', 'taxonomia_regiones_lugar');



/**
 * Registra la taxonomía jerárquica 'tipo_lugar' para categorizar lugares según su tipo.
 *
 * @return void
 */
function taxonomia_tipo_lugar() {
    $labels = array(
        'name'              => 'Tipos de Lugar',
        'singular_name'     => 'Tipo de Lugar',
        'search_items'      => 'Buscar Tipos',
        'all_items'         => 'Todos los Tipos',
        'edit_item'         => 'Editar Tipo',
        'update_item'       => 'Actualizar Tipo',
        'add_new_item'      => 'Añadir Nuevo Tipo',
        'new_item_name'     => 'Nuevo Tipo',
        'menu_name'         => 'Tipos de Lugar',
    );

    $args = array(
        'hierarchical'      => true, 
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tipo-lugar'),
    );

    register_taxonomy('tipo_lugar', array('lugares'), $args);
}
add_action('init', 'taxonomia_tipo_lugar');



/**
 * Agrega un metabox personalizado al editor de 'lugares' para ingresar dirección, coordenadas y precio.
 *
 * @return void
 */

add_action('add_meta_boxes', 'lugares_meta_boxes');

function lugares_meta_boxes() {
    add_meta_box(
        'info_lugar',
        'Información del Lugar',
        'mostrar_campos_lugar',
        'lugares',
        'normal',
        'default'
    );
}



/**
 * Muestra los campos personalizados dentro del metabox del lugar (dirección, latitud, longitud, precio).
 *
 * @param WP_Post $post Post actual del tipo 'lugares'.
 * @return void
 */

function mostrar_campos_lugar($post) {
    $direccion = get_post_meta($post->ID, '_direccion', true);
    $latitud = get_post_meta($post->ID, '_latitud', true);
    $longitud = get_post_meta($post->ID, '_longitud', true);
    $precio = get_post_meta($post->ID, '_precio', true);

    wp_nonce_field('guardar_info_lugar', 'lugares_nonce');
    ?>
    <p>
        <label for="direccion">Dirección:</label><br>
        <input type="text" name="direccion" id="direccion" value="<?php echo esc_attr($direccion); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="longitud">Longitud:</label><br>
        <input type="text" name="longitud" id="longitud" value="<?php echo esc_attr($longitud); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="latitud">Latitud:</label><br>
        <input type="text" name="latitud" id="latitud" value="<?php echo esc_attr($latitud); ?>" style="width: 100%;">
    </p>

    <p>
        <label for="precio">Precio:</label><br>
        <input type="number" name="precio" id="precio" value="<?php echo esc_attr($precio); ?>" style="width: 100%;">
    </p>
    <?php
}



/**
 * Guarda los campos personalizados del metabox al guardar el post.
 *
 * @param int $post_id ID del post que se está guardando.
 * @return void
 */

add_action('save_post', 'guardar_info_lugar');

function guardar_info_lugar($post_id) {

    if (!isset($_POST['lugares_nonce']) || !wp_verify_nonce($_POST['lugares_nonce'], 'guardar_info_lugar')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['direccion'])) {
        update_post_meta($post_id, '_direccion', sanitize_text_field($_POST['direccion']));
    }

    if (isset($_POST['latitud'])) {
        update_post_meta($post_id, '_latitud', sanitize_text_field($_POST['latitud']));
    }

    if (isset($_POST['longitud'])) {
        update_post_meta($post_id, '_longitud', sanitize_text_field($_POST['longitud']));
    }

    if (isset($_POST['precio'])) {
        update_post_meta($post_id, '_precio', sanitize_text_field($_POST['precio']));
    }

}



/**
 * Maneja una solicitud AJAX para filtrar lugares según región, tipo de lugar y presupuesto.
 * Devuelve las ubicaciones encontradas en formato JSON para mostrar en el mapa y tarjetas.
 *
 * @return void
 */

add_action('wp_ajax_filtrar_lugares', 'filtrar_lugares');
add_action('wp_ajax_nopriv_filtrar_lugares', 'filtrar_lugares');

function filtrar_lugares() {

    $region_ciudad = sanitize_text_field($_POST['region_ciudad'] ?? ''); 
    $precio = isset($_POST['presupuesto']) ? floatval($_POST['presupuesto']) : null;
    $tipo_lugar = sanitize_text_field($_POST['tipo_lugar'] ?? '');

    $ciudad_slug = '';
    if (!empty($region_ciudad) && strpos($region_ciudad, '-') !== false) {
        $partes = explode('-', $region_ciudad);
        if (isset($partes[1])) {
            $ciudad_slug = $partes[1];
        }
    }

    $args = array(
        'post_type'      => 'lugares',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );

    $tax_query = array();

    if (!empty($ciudad_slug)) {
        $tax_query[] = array(
            'taxonomy' => 'region',  
            'field'    => 'slug',    
            'terms'    => $ciudad_slug
        );
    }

    if (!empty($tipo_lugar)) {
        $tax_query[] = array(
            'taxonomy' => 'tipo_lugar',  
            'field'    => 'slug',    
            'terms'    => $tipo_lugar
        );
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    // META QUERY: precio
    if (!empty($precio)) {
        $args['meta_query'] = array(
            array(
                'key'     => '_precio',
                'value'   => $precio,
                'type'    => 'NUMERIC',
                'compare' => '<='
            )
        );
    }

    $query = new WP_Query($args);

    $lugares = obtener_datos_lugares($query->posts);

    wp_send_json($lugares);
}



/**
 * Procesa una lista de posts 'lugares' y devuelve un array con información estructurada:
 * coordenadas, título, imagen destacada, precio y descripción.
 *
 * @param array $posts Array de objetos WP_Post.
 * @return array Arreglo de ubicaciones con datos útiles para el frontend.
 */

function obtener_datos_lugares($posts) {
    $ubicaciones = array();

    foreach ($posts as $post) {
        $post_id = $post->ID;

        $latitud  = get_post_meta($post_id, '_latitud', true);
        $longitud = get_post_meta($post_id, '_longitud', true);

        if (!$latitud || !$longitud) {
            continue; 
        }

        if (has_post_thumbnail($post_id)) {
            $imagen_html = get_the_post_thumbnail($post_id, 'medium', ['class' => 'mi-clase-css']);
        } else {
            $ruta_imagen_defecto = 'http://exploraya.local/wp-content/uploads/2025/06/imagen-standar.jpg';
            $imagen_html = '<img src="' . esc_url($ruta_imagen_defecto) . '" alt="Imagen no disponible" class="mi-clase-css">';
        }

        $ubicaciones[] = array(
            'id' => $post_id,
            'title'       => get_the_title($post_id),
            'coords'      => $longitud . ',' . $latitud,
            'img'         => $imagen_html,
            'descripcion' => get_the_excerpt($post_id),
            'precio'      => get_post_meta($post_id, '_precio', true)
        );
    }

    return $ubicaciones;
}



/**
 * Envía un correo al usuario con información sobre un lugar seleccionado desde el formulario.
 * Soporta formularios individuales y grupales.
 *
 * @return void
 */
add_action('wp_ajax_enviar_correo', 'enviar_correo');
add_action('wp_ajax_nopriv_enviar_correo', 'enviar_correo');

function enviar_correo() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $form_type = sanitize_text_field($_POST['form_type'] ?? '');

    $titulo_lugar = get_the_title($post_id);
    $precio = get_post_meta($post_id, '_precio', true);
    $direccion = get_post_meta($post_id, '_direccion', true);

    $correo = sanitize_email($_POST['correo_contacto'] ?? '');

    if (!is_email($correo)) {
        echo 'El correo proporcionado no es válido.';
        wp_die();
    }

    $mensaje = "Hola,<br><br>";
    $mensaje .= "Veo que estás interesado en el sitio <strong>\"$titulo_lugar\"</strong>";

    if ($form_type === 'grupal') {
        $nombres = $_POST['miembro_nombre'] ?? [];
        $edades = $_POST['miembro_edad'] ?? [];

        if (is_array($nombres) && is_array($edades) && count($nombres) > 0) {
            $mensaje .= " y quieres ir con las siguientes personas:<br><ul>";
            foreach ($nombres as $index => $nombre) {
                $nombre_limpio = sanitize_text_field($nombre);
                $edad_limpia = isset($edades[$index]) ? intval($edades[$index]) : 'N/A';
                $mensaje .= "<li>$nombre_limpio ($edad_limpia años)</li>";
            }
            $mensaje .= "</ul>";
        } else {
            $mensaje .= ".<br>";
        }
    } else {
        $nombre = sanitize_text_field($_POST['nombre_individual'] ?? '');
        $edad = sanitize_text_field($_POST['edad_individual'] ?? '');

        $mensaje .= ".<br><br>";
        $mensaje .= "<strong>Nombre:</strong> $nombre<br>";
        $mensaje .= "<strong>Edad:</strong> $edad<br>";
    }

    $mensaje .= "<br>Recuerda que tiene un valor de <strong>$" . number_format(floatval($precio), 0, ',', '.') . "</strong> y queda en <strong>$direccion</strong>.<br>";

    $asunto = "Interés en \"$titulo_lugar\"";
    $cabeceras = array('Content-Type: text/html; charset=UTF-8');

    $enviado = wp_mail($correo, $asunto, $mensaje, $cabeceras);

    echo $enviado ? 'El correo fue enviado correctamente.' : 'Hubo un error al enviar el correo.';

    wp_die();
}




/**
 * Shortcode [mapa_interactivo] para mostrar el mapa interactivo con sus filtros y formulario.
 *
 * @return string HTML generado de los templates cargados.
 */
function mapa_interactivo(){
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/mapa-interactivo.php';
    include plugin_dir_path(__FILE__) . 'templates/formulario.php';
    return ob_get_clean();
}

add_shortcode('mapa_interactivo', 'mapa_interactivo');