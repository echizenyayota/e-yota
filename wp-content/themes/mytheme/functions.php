<?php

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
