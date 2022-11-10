<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, minimum-scale=1">
  <?php wp_head(); ?>
  <link rel="icon" type="image/png"
    href="<?php echo get_theme_file_uri(); ?>/images/favicon.png" />
  <title>
    <?php wp_title('in', true, 'left'); ?>
  </title></a>
</head>

<body>
  <header>
    <nav>
      <ul class="navigation">
        <?php
    wp_nav_menu([
        'theme_location' => 'header-menu-location',
        'items_wrap'     => '%3$s',
        'container'      => ''
    ]);
    ?>
      </ul>
    </nav>
  </header>