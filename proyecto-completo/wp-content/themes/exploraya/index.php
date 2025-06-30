<?php get_header(); // Llama al header.php esquelético ?>

<main id="main" class="site-main">
    <?php
    while ( have_posts() ) :
        the_post();
        the_content(); // Aquí es donde Elementor mostrará el contenido de la página que diseñaste
    endwhile;
    ?>
</main>

<?php get_footer(); // Llama al footer.php esquelético ?>