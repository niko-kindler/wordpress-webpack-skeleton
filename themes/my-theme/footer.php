<div class="spacer-80"></div>

<footer>
<div class="container">
  <div class="footer-primary">
      <p class="footer-headline">Kidz e.V.</p>
      <p class="footer-text">Lützowstr. 28, 10785 Berlin<br />
      kidz@familienzentrum-villaluetzow<br />
      <a href="tel:+43025733816">030-25733816</a>
      </p>
    </div>

<div class="footer-secondary">
    <p class="footer-headline">&nbsp;</p>
<nav class="footer-menu">    
      <?php
        wp_nav_menu([
          'theme_location' => 'footer-menu-location',
          'items_wrap'     => '%3$s',
          'container'      => '',
          'menu_class'     => 'footer'
        ]);
      ?>
</nav>
</div>
</div>

<div class="container">
  <div class="footer-tertiary">
        <p class="footer-bottom">&copy; Kidz e.V. <?php echo date("Y"); ?> </p>
  </div> 
</div>

  <?php wp_footer(); ?>

</footer>

</body>

</html>