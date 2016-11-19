<?php get_header(); ?>

<div class="container">
  <div class="contents">
    <?php if (have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class('gaiyou'); ?>>
        <a href="<?php the_permalink(); ?>">

          <?php if (has_post_thumbnail() ) : // サムネイル画像に関する情報?>
            <?php $postthumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium'); ?>
            <img src="<?php echo $postthumb[0]; ?>" alt="">
          <?php else : // サムネイル画像がない場合?>
            <img src="<?php echo get_template_directory_uri(); ?>/picnic.jpg" alt="">
          <?php endif; // サムネイル画像に関する情報ここまで?>

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
