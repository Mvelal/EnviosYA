<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); // ¡Esencial! Elementor y otros plugins usan esto para añadir sus CSS y JS. ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); // Buena práctica para compatibilidad ?>