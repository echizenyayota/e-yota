<?php get_header(); ?>

<div class="container">
  <div class="contents">
    <?php if (have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class('kiji'); ?>>
        <h1><?php the_title(); ?></h1>
        <time datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
          投稿日:<?php echo get_the_date(); ?>
        </time>
        <?php if (has_post_thumbnail()) : ?>
          <div class="catch">
            <?php the_post_thumbnail( 'large' ); ?>
            <p class="wp-caption-text">
              <?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?>
            </p>
          </div>
        <?php endif; ?>

        <?php the_content(); ?>
      </article>
    <?php endwhile; endif; ?>
  </div>

  <div class="sub">
    <?php get_sidebar(); ?>
  </div>
</div>

<?php get_footer(); ?>
