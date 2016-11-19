<?php get_header(); ?>

<div class="container">
  <div class="contents">
    <?php if (have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class('gaiyou'); ?>>
        <a href="<?php the_permalink(); ?>">

          <img src="<?php echo mythumb('medium'); ?>">

          <div class="text">
            <h1><?php the_title(); ?></h1>

            <div class="kiji-date">
              <i class="fa fa-pencil"></i>
              <time datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">投稿日:<?php echo get_the_date( 'Y-m-d' ); ?></time>
            </div>

            <?php the_excerpt(); ?>
         </div>
         
        </a>
      </article>
    <?php endwhile; endif; ?>
  </div>

  <div class="sub">
    <?php get_sidebar(); ?>
  </div>
</div>

<?php get_footer(); ?>
