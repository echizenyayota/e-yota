<?php get_header(); ?>
<div class="sub-header">
  <div class="google-mobile">
    <div class="ad1">
      <p>
        <!-- <img src="" width=320 height="50" > -->
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
          <!-- e-yota_mobile -->
          <ins class="adsbygoogle"
               style="display:block"
               data-ad-client="ca-pub-4555449890300852"
               data-ad-slot="4080387032"
               data-ad-format="auto"></ins>
          <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
      </p>
    </div>
  <div>
<div class="bread">
  <ol>
    <li>
      <a href="<?php echo esc_url ( home_url() ); ?>"><i class="fa fa-home"></i><span>TOP</span></a>
    </li>
    <li>
      <a><?php the_archive_title(); ?></a>
    </li>
</ol>
</div>
</div>
<div class="container">
  <div class="contents">

    <h1><?php the_archive_title(); ?>の記事</h1>

    <?php if (have_posts()): while(have_posts()): the_post(); ?>

      <?php get_template_part('gaiyou', 'medium'); ?>

    <?php endwhile; endif; ?>
    <div class="pagination pagination-index">
      <?php echo paginate_links( array(
        'type' => 'list',
        'end_size' => '2',
        'mid_size' => '3',
      )); ?>
    </div>
  </div>

  <div class="sub">
    <?php get_sidebar(); ?>
  </div>
</div>

<?php get_footer(); ?>
