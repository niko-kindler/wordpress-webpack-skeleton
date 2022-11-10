<footer>
  <nav>
    <ul>
      <?php
    wp_nav_menu([
        'theme_location' => 'footer-menu-location',
        'items_wrap'     => '%3$s',
        'container'      => ''
    ]);
?>
    </ul>
  </nav>


  <?php wp_footer(); ?>
  </body>

  </html>