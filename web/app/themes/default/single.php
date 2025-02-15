<?php get_header(); ?>
	<main>
		<section>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <article>
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; else : ?>
                <p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
            <?php endif; ?>
        </section>
	</main>
<?php get_footer(); ?>