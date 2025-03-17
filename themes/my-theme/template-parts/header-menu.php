<header>

<a href="<?php echo get_home_url();?>" class="logo"><img src="<?php echo get_theme_file_uri('src/images/Logo.png');?>" class="logo-img" /></a>

<input class="side-menu" type="checkbox" id="side-menu" />
<label class="hamb" for="side-menu"><span class="hamb-line"></span></label>

<nav class="nav">
    <?php
        wp_nav_menu([
            'theme_location' => 'header-menu-location',
            'container'      => 'ul',
            'menu_class'     => 'menu'
            ]);
    ?>
</nav>
 
</header>

<div class="infobar">
    <p><a href="tel:03025733816"><span class="dashicons call-button dashicons-phone"></span> <span class="contactinfo">030 - 25 73 38 16</span></a></p>
    <p><a href="mailto:info@familienzentrum-villaluetzow.de"><span class="dashicons mail-button dashicons-email"></span> <span class="contactinfo">info@familienzentrum-villaluetzow.de</span></a></p>
</div>