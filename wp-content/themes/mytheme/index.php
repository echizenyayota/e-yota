<?php get_header(); ?>

<div class="container">
  <div class="contents">
    <?php if (have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class('gaiyou'); ?>>
        <a href="<?php the_permalink(); ?>">
          <h1><?php the_title(); ?></h1>
          <?php the_excerpt(); ?>
        </a>
      </article>
    <?php endwhile; endif; ?>
  </div>

  <div class="sub">
    <?php get_sidebar(); ?>
  </div>
</div>

<?php get_footer(); ?>
