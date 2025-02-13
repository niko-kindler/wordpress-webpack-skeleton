<?php
/*
Plugin Name: Custom Functions
Author: Nikolas Kindler
Version: 1.1
*/

function my_scripts() {
	wp_enqueue_script( 'google-maps_js', '/wp-content/mu-plugins/js/google-maps.js', array('jquery'), microtime(), true );
	wp_enqueue_style( 'acf-map', '/wp-content/mu-plugins/css/acf-map.css');
  }
  
add_action( 'wp_enqueue_scripts', 'my_scripts' );

add_filter('the_content', 'do_shortcode');

function current_monthf( ) {

	$month = date("m");

	if($month=="01") { $monat = "Januar"; }
	if($month=="02") { $monat = "Februar"; }
	if($month=="03") { $monat = "März"; }
	if($month=="04") { $monat = "April"; }
	if($month=="05") { $monat = "Mai"; }
	if($month=="06") { $monat = "Juni"; }
	if($month=="07") { $monat = "Juli"; }
	if($month=="08") { $monat = "August"; }
	if($month=="09") { $monat = "September"; }
	if($month=="10") { $monat = "Oktober"; }
	if($month=="11") { $monat = "November"; }
	if($month=="12") { $monat = "Dezember"; }

	echo $monat;
}

function current_dayf( ) {

	$day = date("D");

	if($day=="Mon") { $tag = "Montag"; }
	if($day=="Tue") { $tag = "Dienstag"; }
	if($day=="Wed") { $tag = "Mittwoch"; }
	if($day=="Thu") { $tag = "Donnerstag"; }
	if($day=="Fri") { $tag = "Freitag"; }
	if($day=="Sat") { $tag = "Samstag"; }
	if($day=="Sun") { $tag = "Sonntag"; }

	echo $tag;
}

function location_map_f(){
	echo "<div class='acf-map'>";

	$map_locations = new WP_Query(
        array(
		  'posts_per_page'   => -1,
          'post_type' => array('unterkunfte','reisen'),
        ));
      while ( $map_locations->have_posts() ) {
      $map_locations->the_post();
      $location = get_field('google_map');

		if (!$location['lat'] && !$location['lng']){
			echo "<!-- Übersprungen -->";
		}
		else { 

      ?>
      <div class="marker" data-lat="<?php echo $location['lat'] ?>" data-lng="<?php echo $location['lng'] ?>">
        <div class="google-map-tooltip-minwidth">
          <div><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
        </div>
      </div>
	  <?php }}
	  
	  echo "</div>";

      wp_reset_postdata(); 

}

function location_map_f_reise(){

	$location = get_field('google_map');

?>
	<div class='acf-map'>
      <div class="marker" data-lat="<?php echo $location['lat'] ?>" data-lng="<?php echo $location['lng'] ?>">
        <div class="google-map-tooltip-minwidth">
          <div><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
        </div>
      </div>
	</div>

<?php
      wp_reset_postdata(); 

}

function location_map_f_country($atts, $content = null){

	$a = shortcode_atts( array(
        'land' => ''
    ), $atts );


	$tax_query[] = array(
		'taxonomy' => 'land',
		'field' => 'slug',
		'terms' => $a['land'],
	);

	
		$args = array(
			'tax_query' => array($tax_query),
			'post_type' => array('unterkunfte','reise'),
			'posts_per_page'   => -1,
			'post__not_in' => array( $post_id )
		);

	echo "<div class='acf-map'>";

	$map_locations = new WP_Query($args);

      while ( $map_locations->have_posts() ) {
      $map_locations->the_post();
	  $location = get_field('google_map');
	  
	  if (!$location['lat'] && !$location['lng']){
		echo "<!-- Übersprungen -->";
		}
		else { 

      ?>
      <div class="marker" data-lat="<?php echo $location['lat'] ?>" data-lng="<?php echo $location['lng'] ?>">
        <div class="google-map-tooltip-minwidth">
          <div><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
        </div>
      </div>
	  <?php }}
	  
	  echo "</div>";

      wp_reset_postdata(); 

}

function register_ysops_shortcodes(){
	add_shortcode('current_month','current_monthf');
	add_shortcode('current_day','current_dayf');
	add_shortcode('location_map','location_map_f');
	add_shortcode('location_map_reise','location_map_f_reise');
	add_shortcode('location_map_country','location_map_f_country');
}

add_action('init','register_ysops_shortcodes');

function acf_gmaps(){
    wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDwtSs08BKrTVXlPprNbXJ1bbk5ervbsv4', array(), '3', true );
    wp_enqueue_script( 'google-map-init', get_theme_file_uri('/js/google-maps.js'), array('google-map', 'jquery'), '0.1' , true );
}

add_action ('wp_enqueue_scripts', 'acf_gmaps');

// API-Key für Google Maps (ACF)
function my_acf_google_map_api( $apiKey_google ){
	
	$apiKey_google['key'] = 'AIzaSyDwtSs08BKrTVXlPprNbXJ1bbk5ervbsv4';
	
	return $apiKey_google;
	
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

?>