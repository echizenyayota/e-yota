<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>
    <?php wp_title( '|', true, 'right'); ?>
    <?php bloginfo('name'); ?>
  </title>
  <meta name="viewport" content="width=device-width", initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>"
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>
<header>
  Header
</header>
