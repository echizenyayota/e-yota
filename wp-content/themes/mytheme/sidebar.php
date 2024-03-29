<!-- <?php dynamic_sidebar('ad'); ?> -->

<?php
  $location_name = 'pickup';
  $locations = get_nav_menu_locations();
  $myposts = wp_get_nav_menu_items($locations[$location_name]);
  if ($myposts) : ?>

<aside class="mymenu mymenu-large">
  <h2>NHK朝ドラ カムカムエヴリバディ</h2>
  <ul>
    <?php foreach($myposts as $post):
       if ($post->object == 'post'):
       $post = get_post($post->object_id);
       setup_postdata($post); ?>
      <li>
        <a href="<?php the_permalink(); ?>">
          <!-- <div class="thumb" style="background-image: url(<?php echo mythumb('medium'); ?>)"></div> -->
          <div class="text">
            <?php the_title(); ?>
          </div>
        </a>
      </li>
    <?php endif; endforeach; ?>
  </ul>
</aside>
<?php wp_reset_postdata(); endif; ?>


<?php
  $the_slug = 'come_come_everybody';
  $myposts = get_posts( array(
    'category_name' => $the_slug,
    'post_type' => 'post',
    'posts_per_page' =>'3',
    'meta_key' => 'postviews',
    'orderby' => 'meta_value_num',
  ));
  if ($myposts) : ?>

<!-- 最新の記事 -->
<aside class="mymenu mymenu-thumb">
  <h2>最新記事</h2>
  <ul>
  <?php
    $args = array(
      'posts_per_page' => 3 // 表示件数の指定
    );
    $posts = get_posts( $args );
    foreach ( $posts as $post ): // ループの開始
    setup_postdata( $post ); // 記事データの取得
  ?>
  <li>
      <a href="<?php the_permalink(); ?>" class="img_link" aria-label="thumbnail">
        <div class="thumb" style="background-image: url(<?php echo mythumb('thumbnail'); ?>)"></div>
      </a>
      <div class="text">
        <a href="<?php the_permalink(); ?>">
          <?php the_title(); ?>
        </a>
        <?php if (has_category()) : ?>
          <?php
            $postcat = get_the_category();
            $catid = $postcat[1]->cat_ID;
            $cat_link = get_category_link($catid);
          ?>
          <span class="new_article">
            <?php echo $postcat[1]->name; ?>
          </span>
        <?php endif; ?>
        <?php
          $year   = get_the_date( 'Y' );
          $month  = get_the_date( 'm' );
        ?>
          <span class="new_article">
              <?php echo get_the_date(); ?>
          </span>
      </div>

  </li>
  <?php
    endforeach; // ループの終了
    wp_reset_postdata(); // 直前のクエリを復元する
  ?>
  </ul>
</aside>

<!-- 人気の記事 -->
<aside class="mymenu mymenu-thumb">
  <h2>カムカムエヴリバディ人気記事</h2>
  <ul>
    <?php foreach($myposts as $post): setup_postdata($post); ?>
      <li>
        <a href="<?php the_permalink(); ?>">
        <div class="thumb" style="background-image: url(<?php echo mythumb('thumbnail'); ?>)"></div>
        <div class="text">
            <span class="pv">
             <?php
               $value = get_post_meta( get_the_ID(), 'postviews', true );
               if( $value ){
                 echo intval($value) . 'PV';
               }
             ?>
            </span>
          <?php the_title(); ?>
          <?php if (has_category()) : ?>
            <?php $postcat = get_the_category_descendants(); ?>
             <span>
                 <time datetime="<?php the_time('c'); ?>"><?php echo get_the_date(); ?></time>
             </span>
          <?php endif; ?>
        </div>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</aside>
<?php wp_reset_postdata(); endif; ?>

<?php dynamic_sidebar('submenu'); ?>
