<?php
/**
 * Template Name: Full Width Page with Top Title
 */

get_header();

get_template_part( 'templates/top-title' ); ?>

<div class="mh-layout mh-top-title-offset">

    <?php while ( have_posts() ) : the_post();

        get_template_part( 'templates/content', 'page' );

        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

    endwhile; ?>

</div>

<?php get_footer();
