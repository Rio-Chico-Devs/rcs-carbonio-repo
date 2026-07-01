<?php get_header(); ?>
<main>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article <?php post_class(); ?>>
<div><?php the_content(); ?></div>
</article>
<?php endwhile; else : ?><p>Nessun contenuto trovato.</p><?php endif; ?>
</main>
<?php get_footer(); ?>
