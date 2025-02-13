<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, minimum-scale=1.0">
  <?php wp_head(); ?>
  <link rel="icon" type="image/png" href="<?php echo get_theme_file_uri(); ?>/images/favicon.png" />
  <title>
    <?php wp_title('in', true, 'left'); ?>
  </title></a>
</head>

<body>

<?php get_template_part('template-parts/header', 'menu'); ?>
