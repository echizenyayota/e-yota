<?php

// 子テーマの読み込み
// add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
// function theme_enqueue_styles() {
//     wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
//     wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
// }

// 抜粋の文字数
function my_length($length) {
  return 30;
}
add_filter('excerpt_mblength', 'my_length');

// 抜粋の省略記号
function my_more($more) {
  return '...';
}
add_filter('excerpt_more', 'my_more');

// コンテンツの最大幅
if (!isset($content_width)) {
  $content_width = 747;
}

//YouTubeのビデオ：<div>でマークアップ
function ytwrapper($return, $data, $url) {
	if ($data->provider_name == 'YouTube') {
		return '<div class="ytvideo">'.$return.'</div>';
	} else {
		return $return;
	}
}
add_filter('oembed_dataparse','ytwrapper',10,3);

//YouTubeのビデオ: キャッシュをクリア
function clear_ytwrapper($post_id) {
  global $wp_embed;
  // var_dump($wp_embed);
  // exit;
  $wp_embed->delete_oembed_caches($post_id);
}
add_action('pre_post_update', 'clear_ytwrapper');

add_theme_support( 'title-tag' );

// アイキャッチ画像の指定
add_theme_support('post-thumbnails');

// 編集画面の設定(h1を削除、補足情報と注意書きを追加)
function editor_setting($init) {
  $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre';
  $style_formats = array(
    array(
      'title' => '補足情報',
      'block' => 'div',
      'classes' => 'point'
    ),
    array(
      'title' => '注意書き',
      'block' => 'div',
      'classes' => 'attention'
    )
  );

  $init['style_formats'] = json_encode($style_formats);

  return $init;
}
add_filter('tiny_mce_before_init', 'editor_setting');

// スタイルメニューを有効化
function add_stylemenu($buttons) {
  array_splice($buttons, 1, 0, 'styleselect');
  return $buttons;
}
add_filter('mce_buttons_2', 'add_stylemenu');

// エディタスタイルシート EdgeとIEでも読み込めるようにする
add_editor_style(get_template_directory_uri() . '/editor-style.css?ver=' . date('U'));
add_editor_style('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

// サムネイル画像
function mythumb( $size ) {

  global $post;

  if (has_post_thumbnail() ) {
    $postthumb = wp_get_attachment_image_src( get_post_thumbnail_id(), $size);
    $url = $postthumb[0];
    // var_dump($url);
    // 小かっこは、パターンにマッチした部分文字列を取得したい場合に使います。https://goo.gl/nqdwvE
  } elseif(preg_match('/wp-image-(\d+)/s', $post->post_content, $thumbid)) {
    $postthumb = wp_get_attachment_image_src( $thumbid[1], $size);
    $url = $postthumb[0];
  } else {
    $url = get_template_directory_uri() . '/ecoteki-image.png';
  }
  return esc_url( $url );

}

// mythumb()関数の無害化 GitとGitHubの動作確認
// function the_mythumb() {
//   echo esc_url( mythumb() );
// }

// カスタムメニュー
register_nav_menu( 'sitenav', 'サイトナビゲーション');
register_nav_menu( 'pickup', 'おすすめ記事');

register_sidebar(array('name' => 'フッター1', 'id' => 'sidebar-1'));


// register_sidebar(array('sidebar-2' => 'フッター２'));
// register_sidebar(array('sidebar-3' => 'フッター３'));

// トグルボタン
function eyota_scripts() {
  wp_enqueue_script('navbtn-script', get_template_directory_uri() . '/navbtn.js', array('jquery'));
  wp_enqueue_style('eyota-style', get_stylesheet_uri());
}
add_action( 'wp_enqueue_scripts', 'eyota_scripts', 99);

// /*  存在しないページを指定された場合は 404 ページを表示する  */
// function redirect_404() {
//   //メインページ・シングルページ・アーカイブ・固定ページ 以外の指定の場合 404 ページを表示する
//   if(is_home() || is_single() || is_category() || is_tag() || is_page()) {
//     return;
//   }
//   include(TEMPLATEPATH . '/404.php');
//   exit;
// }
// add_action('template_redirect', 'redirect_404');

// 前後の記事に関するメタデータの出力を禁止（Firefox対策）
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// ウイジェットエリア
function mytheme_register_sidebar() {
  register_sidebar( array(
    'id' => 'submenu',
    'name' => 'サブメニュー',
    'description' => 'サイドバーに表示するウイジェットを指定',
    'before_widget' => '<aside id="%1$s" class="mymenu widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h2 class="widgettitle">',
    'after_title' => '</h2>'
  ));

  register_sidebar( array(
    'id' => 'ad',
    'name' => '広告',
    'description' => 'サイドバーに表示する広告を指定',
    'before_widget' => '<aside id="%1$s" class="myad widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h2 class="widgettitle">',
    'after_title' => '</h2>'
  ));
}
add_action( 'widgets_init', 'mytheme_register_sidebar' );

// 検索フォーム
 add_theme_support ('html5', array('search-form'));

 // テーマのタグクラウドのパラメータ変更
function my_tag_cloud_filter($args) {
    $myargs = array(
        'smallest' => 9, // 最小文字サイズは 10pt
        'largest' => 9, // 最大文字サイズは 10pt
        'number' => 50,  // 一度に表示するのは80タグまで（0で無限)
        'echo' => false,  // wordpress4.4以前の人はこの行は不要
        'orderby' => 'count', //使用頻度順
        'order' => 'DESC',  // 降順
        'show_count' => true, // タグの数を表示
    );
    return $myargs;
}
add_filter('widget_tag_cloud_args', 'my_tag_cloud_filter');

// 画像サイズに「大」と「中」を含める
add_filter('image_size_names_choose', 'my_media_insert_all_sizes');
function my_media_insert_all_sizes( $default_sizes ){
	$sizes = get_intermediate_image_sizes();
  update_option('my_dump_value',var_export($default_sizes, true) );
  foreach( $sizes as $size ) {
		if( ! array_key_exists( $size, $default_sizes ) ) {
			$default_sizes[ $size ] = ucfirst($size);
		}
	}
  return $default_sizes;
}

// ウィジェットメニューの整形（年月の右横に年月を並べる）
function my_get_archives_link( $link_html, $url, $text, $format, $before, $after ) {

	if ( 'html' == $format ) {
    $link_html = "\t<li>$before<a href='$url'>$text$after</a></li>\n";
	}
	return $link_html;
}

add_filter( 'get_archives_link', 'my_get_archives_link', 10, 6 );

// フッター部分にあるカテゴリーメニューの記事数を水色に装飾する
function my_list_categories( $output ) {
	$output = preg_replace( '/<\/a>\s(\(\d+\))/', ' $1</a>', $output );
	return $output;
}

add_filter ( 'wp_list_categories', 'my_list_categories' );

// 現在の投稿が属するカテゴリーのうち最下層のみのカテゴリー（オブジェクト）の配列を返す
function get_the_term_descendants( $id, $taxonomy ) {
	$terms = get_the_terms( $id, $taxonomy );
	if ( ! is_array( $terms ) )
		return array();
	$descendants = $terms;
	foreach( $terms as $key => $term ) {
		foreach( $terms as $sub_term ) {
			if ( $term->term_id == $sub_term->parent ) {
				unset( $descendants[$key] );
				break;
			}
		}
	}
	return array_values( $descendants );
}

function get_the_category_descendants( $id = false ) {
	return get_the_term_descendants( $id, 'category' );
}


add_image_size('large-thumbnail', 650, 350, true);

// ページによってCSSやJavaScriptの読み込みを制御
function performance_dequeue_scripts() {
	if ( is_home() || is_archive()) {
		wp_deregister_script( 'jquery' );
  }
  /* Gutenbergを使用するときはif文の中に入れる */
  wp_deregister_style( 'wp-block-library' );
}
if ( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'performance_dequeue_scripts', 99 );
}

// PWAアイコン表示用 'short_name'の記述を追加
add_filter( 'web_app_manifest', function( $manifest ) {
	$manifest['short_name'] = 'e_yota';
	return $manifest;
} );

// レンダリングを妨げるリソースの除外
function add_defer_to_scripts( $tag, $handle ) {
  if ( !preg_match( '/\b(async|defer)\b/', $tag ) ) {
    return str_replace('src', 'defer src', $tag );
  }
  return $tag;
}

if ( !is_admin() ) {
  add_filter( 'script_loader_tag', 'add_defer_to_scripts', 10, 2 );
}

// wp_targeted_link_rel関数の活用で target属性がついているaタグにrel="noopener noreferrer"の追加
add_filter( 'the_content', 'wp_targeted_link_rel' );


//  記事本文中target属性をもつaタグにスクリーンリーダーを読み込む
function screen_reader( $text ) {
  if ( stripos( $text, 'target' ) !== false && stripos( $text, '<a ' ) !== false ) {
      // spanタグの追加(コンテンツに script/style タグを含まないことを前提にした場合)
      // $after = '<span class="screen-reader-text">(新しいタブを開く)</span><span aria-hidden="true" class="dashicons dashicons-external"></span>';
      // $text = preg_replace( '|(<a\s[^>]*target\s*=[^>]*>.*)(</a>)|i', '${1}' . $after . '${2}', $text );
      // spanタグの追加(コンテンツに script/styleタグを含まないことを前提する場合)
      $after = '<span class="screen-reader-text">(新しいタブで開く)</span><span aria-hidden="true" class="dashicons dashicons-external"></span>';

      $script_and_style_regex = '/<(script|style).*?<\/\\1>/si';

      preg_match_all( $script_and_style_regex, $text, $matches );
      $extra_parts = $matches[0];
      $html_parts  = preg_split( $script_and_style_regex, $text );
      foreach ( $html_parts as &$part ) {
        $part = preg_replace( '|(<a\s[^>]*target\s*=[^>]*>.*)(</a>)|i', '${1}' . $after . '${2}', $part );
      }

    $text = '';
    for ( $i = 0; $i < count( $html_parts ); $i++ ) {
      $text .= $html_parts[ $i ];
      if ( isset( $extra_parts[ $i ] ) ) {
        $text .= $extra_parts[ $i ];
      }
    }
  }
  return $text;
}

if ( !is_admin() ) {
  add_filter( 'the_content', 'screen_reader', 10, 2 );
}

// ウィジェットの検索フォームにaria-label属性を追記する関数の追記
function my_search_form( $form ) {
	$form = str_replace( 'name="s"', 'name="s" aria-label="search-box"', $form );
	return $form;
}
add_filter( 'get_search_form', 'my_search_form' );

// maskable_iconの追加
add_filter( 'web_app_manifest', function( $manifest ) {
	$manifest['icons'][] = [
		'src'     => home_url( '/android-app.png' ),
		'sizes'   => '192x192',
		'type'    => 'image/png',
		'purpose' => 'any maskable',
	];
	return $manifest;
} );







