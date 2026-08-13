<?php get_header(); ?>

<main>
    
    <?php
    woocommerce_breadcrumb();
?>

<section class="not-found section--pb_M"> 
	<div class="container not-found__container"> 
		<div class="not-found__sign flex-center">
    		<div class="not-found__letter rel ff--title">4</div>
    		<div class="not-found__img shrink0"> 
    			<picture> 
                    <img src="<?= get_template_directory_uri() ?>/assets/pics/404.png" alt="" loading="lazy" width="150" height="150" class="contain-image">
                </picture>
    		</div>
    		<div class="not-found__letter rel ff--title">4</div>
		</div>
		<h1 class="section-title not-found__title ff--title fs--italic  t--center">Упс, щось пішло не так! Повертаємося до затишку</h1>
		<a class="btn btn--full_accent not-found__link rel o-hid d-block mx text--btn-m mt--24" href="<?= home_url() ?>">Головна </a>
	</div>
</section>

</main>

<?php get_footer(); ?>