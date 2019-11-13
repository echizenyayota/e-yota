  <?php get_header(); ?>

<div class="sub-header">
<div class="bread">
  <ol>
    <li>
      <a href="<?php echo esc_url( home_url() ); ?>">TOP<span>TOP</span></a>
    </li>
    <li>
      <?php if ( has_category()) : ?>
        <?php $postcat = get_the_category(); ?>
        <?php echo get_category_parents( $postcat[0], true, '</li><li>'); ?>
      <?php endif; ?>
      <a><?php the_title(); ?></a>
    </li>
</ol>
</div>
</div>

<div class="container">
  <div class="contents">
    <?php if (have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class('kiji'); ?>>

        <h1><?php the_title(); ?></h1>

        <div class="kiji-date">
          <time datetime="<?php the_time('c'); ?>">
            投稿日:<?php echo get_the_date(); ?>
          </time>
          <?php if (get_the_modified_date( 'Y-m-d' ) > get_the_date( 'Y-m-d' )) : ?>
            |
          <time datetime="<?php echo get_the_modified_date( 'Y-m-d' ); ?>">
            更新日:<?php echo get_the_modified_date(); ?>
          </time>
          <?php endif; ?>
        </div>
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
        <?php if (has_post_thumbnail()) : ?>
          <div class="catch">
            <?php the_post_thumbnail( 'large' ); ?>
            <p class="wp-caption-text">
              <?php
                $post_thumbnail_id = get_post_thumbnail_id();
                if($post_thumbnail_id) {
                  echo get_post( $post_thumbnail_id )->post_excerpt;
                 }
                ; ?>
            </p>
          </div>
        <?php else: ?>
          <div class="catch">
            <?php echo '<img src="' . get_bloginfo('template_directory') . '/ecoteki-image.png' . '" width="592" height="400" alt="thumbnail" />'; ?>
          </div>
        <?php endif; ?>

        <div class="kiji-body">
          <?php the_content(); ?>
        </div>

        <div class="google-ad">
          <div class="ad2">
            <p>
              <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
              <!-- e-yota2 -->
              <ins class="adsbygoogle"
                   style="display:inline-block;width:300px;height:250px"
                   data-ad-client="ca-pub-4555449890300852"
                   data-ad-slot="1126920633"></ins>
              <script>
              (adsbygoogle = window.adsbygoogle || []).push({});
              </script>
            </p>
          </div>
          <div class="ad3">
            <p>
              <?php if( in_category('dmm-en') ) : ?>
              <ins class="widget-banner"></ins><script class="widget-banner-script" src="https://widget-view.dmm.com/js/banner_placement.js?affiliate_id=echizenya35-001&banner_id=204_300_250"></script>
              <?php endif; ?>
              <!-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
                <!-- e-yota3 -->
                <!-- <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-4555449890300852"
                     data-ad-slot="2603653834"></ins> -->
                <!-- <script>
                  (adsbygoogle = window.adsbygoogle || []).push({});
                </script> -->
            </p>
          </div>
        </div>

        <div class="share">
          <ul>
            <li>
              <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode( get_the_title() . ' - ' . get_bloginfo('name') ); ?>&amp;url=<?php echo urlencode( get_permalink() ); ?>&amp;via=echizenya_yota"
            	onclick="window.open(this.href, 'SNS', 'width=500, height=300, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-tw">
                ツイート 
              </a>
            </li>
            <li>
              <a href="http://www.facebook.com/share.php?u=<?php echo urlencode( get_permalink() ); ?>"
            	onclick="window.open(this.href, 'SNS', 'width=500, height=500, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-fb">
                シェア
              </a>
            </li>
            <li>
              <a href="//b.hatena.ne.jp/entry/<?php echo urlencode( get_permalink() ); ?>" onclick="window.open(this.href, 'SNS', 'width=1000, height=400, menubar=no, toolbar=no, scrollbars=yes'); return false;"  target="_blank" class="share-hatena">
                はてな<span>ブックマーク</span>
              </a>
            </li>
          </ul>
        </div>

        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle"
          style="display:block"
          data-ad-format="autorelaxed"
          data-ad-client="ca-pub-4555449890300852"
          data-ad-slot="2289667369"></ins>
          <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
          </script>

          <div class="kiji-tag">
            <?php the_tags('<ul><li>', '</li><li>','</li></ul>'); ?>
          </div>
          <div class="dmm-en">
          <?php if( in_category('dmm-en') ) : ?>
            <ins class="widget-banner"></ins><script class="widget-banner-script" src="https://widget-view.dmm.com/js/banner_placement.js?affiliate_id=echizenya35-001&banner_id=204_728_90"></script>
            <?php endif; ?>
          </div>
        <?php wp_reset_postdata(); ?>

        <?php
          $the_slug = 'non';
          $myposts = get_posts( array(
            'category_name' => $the_slug,
            'post_type' => 'post',
            'posts_per_page' =>'8',
            'orderby' => 'rand'
          ));
          if ($myposts) : ?>

          <aside class="mymenu mymenu-thumb">
            <h2>のんさん関連記事</h2>
            <ul>
              <?php foreach($myposts as $post): setup_postdata($post); ?>
                <li>
                  <a href="<?php the_permalink(); ?>">
                  <div class="thumb" style="background-image: url(<?php echo mythumb('thumbnail'); ?>)"></div>
                  <div class="text">
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

      </article>
    <?php endwhile; endif; ?>
  </div>

  <div class="sub">
    <?php get_sidebar(); ?>
  </div>
</div>

<?php get_footer(); ?>

<?php // アクセス数の記録(ログインしているユーザーは除外)
  if (!is_user_logged_in() ) {
    $count_key = 'postviews';
    $count = (int)get_post_meta($post->ID, $count_key, true);
    if (!is_int($count)) {
        $count = 0;
    }
    $count++;
    update_post_meta($post->ID, $count_key, $count);
  }
?>
