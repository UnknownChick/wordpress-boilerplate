<?php get_header(); ?>
<main>
    <h1>Hello world !</h1>
    
    <img src="<?= get_stylesheet_directory_uri() ?>/assets/imgs/test.jpg" alt="">

    <?= do_shortcode('[site_map type="pages"]'); ?>

    <?php get_template_part('partials/components/list', 'blog'); ?>
</main>
<?php get_footer(); ?>
