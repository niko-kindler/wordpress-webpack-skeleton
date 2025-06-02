<?php
/*
Plugin Name: Foto Galerie
Author: Nikolas Kindler
Version: 1.0
*/

function gallery_style() {
    if (is_admin()) return;

    wp_enqueue_style( 'foto-gallery', '/wp-content/mu-plugins/css/foto-gallery.css' );
    wp_enqueue_script( 'foto-gallery', '/wp-content/mu-plugins/js/foto-gallery.js' );
}
add_action( 'wp_enqueue_scripts', 'gallery_style' );

function bildergalerie_shortcode($atts) {
  ob_start();


  // Removed the extract function and accessed attributes directly
  $urls = isset($atts['urls']) ? explode(',', $atts['urls']) : [];
  $titles = isset($atts['titles']) ? explode(',', $atts['titles']) : [];

  // Check if URLs are provided and if the number of URLs matches the number of titles
  if(empty($urls)) {
    return 'Error: No image URLs provided.';
  }
  if(count($urls) !== count($titles)) {
    return 'Error: The number of image URLs does not match the number of titles.';
  }

  $anzahl_bilder = count($urls);

?>

<div class="image-container">

<ul class="image-gallery">
    
<?php
    $i = 0;
    while ($i < $anzahl_bilder) {
?>  

    <li>
      <img src="<?php echo $urls[$i]; ?>" alt="" />
      <?php      
        if ($titles[$i]) { 
            echo "<div class=\"overlay\"><span class=\"overlay-text\">". $titles[$i] ."</span></div>"; 
        }
      ?>
    </li>

<?php
    $i++;
    
    }
?>  

</ul>

</div>

<?php

$ret = ob_get_contents();
   ob_end_clean();
   return $ret;

}

add_shortcode( 'bildergalerie', 'bildergalerie_shortcode' );

?>
