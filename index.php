<?php get_header(); ?>

<main>
    
    <?php
    woocommerce_breadcrumb();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		the_content();

	endwhile;
endif;
?>

</main>

<?php get_footer(); ?>