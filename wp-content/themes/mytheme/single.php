<?php get_header(); ?>

<div class="container">
  <div class="contents">
    <?php if (have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class('kiji'); ?>>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </article>
    <?php endwhile; endif; ?>
  </div>

  <div class="sub">
    Sidebar
  </div>
</div>


<?php get_footer(); ?>
