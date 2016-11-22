<?php
  $myposts = get_posts( array(
    'post_type' => 'post',
    `posts_per_page` =>'5'
  ));
  if ($myposts) : ?>

<aside class="mymenu mymenu-thumb">
  <h2>新着記事</h2>
  <ul>
    <?php foreach($myposts as $post): setup_postdata($post); ?>
      <li>
        <a href="<?php the_permalink(); ?>">
        <div class="thumb" style="background-image: url(<?php echo mythumb('thumbnail'); ?>)"></div>
        <div class="text">
          <?php the_title(); ?>
        </div>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</aside>
<?php wp_reset_postdata(); endif; ?>
