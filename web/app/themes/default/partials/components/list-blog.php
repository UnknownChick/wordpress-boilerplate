<?php
$posts_per_page_blog = get_query_var('posts_per_page_blog', -1);
$args = [
    'post_type' => 'post',
    'posts_per_page' =>  $posts_per_page_blog,
    'orderby' => 'date',
];
$posts = new WP_Query($args); ?>

<div class="row row-xl gap-1">
    <?php if ($posts->have_posts()) : ?>
        <?php while ($posts->have_posts()) :
            $posts->the_post(); ?>
            <div class="col-50">
                <article class="article">
                    <div class="article__image" style="background-image: url('<?php the_post_thumbnail_url(); ?>');">

                    </div>
                    <div class="article__content">
                        <p class="article__content__date"><?php the_date(); ?></p>
                        <h3 class="article__content__title"><?php the_title(); ?></h3>
                        <div class="article__content__excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="article__content__link">Lire en entier</a>
                    </div>
                </article>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
