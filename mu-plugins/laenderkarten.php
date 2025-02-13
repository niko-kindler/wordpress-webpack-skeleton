<?php
/*
Plugin Name: Länderkarten
Author: Nikolas Kindler
Version: 1.0
*/

function card_style() {
	wp_enqueue_style( 'card-style', '/wp-content/mu-plugins/css/card-style.css');
  }
  
add_action( 'wp_enqueue_scripts', 'card_style' );

function custom_posts_orderby( $orderby_statement ) {
    global $wpdb;
    
    $orderby_statement = "(
        SELECT GROUP_CONCAT(name ORDER BY name ASC) 
        FROM $wpdb->terms 
        WHERE term_id IN (
            SELECT term_id 
            FROM $wpdb->term_taxonomy tt 
            LEFT JOIN $wpdb->term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id 
            WHERE tt.taxonomy = 'land' AND tr.object_id = $wpdb->posts.ID
        )
    ) ASC,
    (
        SELECT GROUP_CONCAT(name ORDER BY name ASC) 
        FROM $wpdb->terms 
        WHERE term_id IN (
            SELECT term_id 
            FROM $wpdb->term_taxonomy tt 
            LEFT JOIN $wpdb->term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id 
            WHERE tt.taxonomy = 'region' AND tr.object_id = $wpdb->posts.ID
        )
    ) ASC";

    return $orderby_statement;
}

add_filter('posts_orderby', 'custom_posts_orderby');

function card_shortcode($atts) {
    ob_start();

$args = shortcode_atts(array(
                        'land' => 'alle',
                        'reiseart' => 'alle',
                        'title' => 'Unsere Reisen'
                    ),                     
                    $atts);

$land = $args['land'];
$reiseart = $args['reiseart'];
$title = $args['title'];


// Wenn alle Länder angezeigt werden sollen:
if ( $land == "alle") {
    $query_args = array('post_type' => 'reisen',
                        'posts_per_page' => '-1'
                );
}
// Wenn nur ein Land angezeigt werden soll:
else {
    $query_args = array('post_type' => 'reisen',
                        'posts_per_page' => '-1',
                        'tax_query' => array(
                                       array(
                                            'taxonomy' => 'land',
                                            'field' => 'name',
                                            'terms' => $land
                                            ))
                );
}

?>

    </div>
</div>

<div class="container margin"><a name="<?php echo $reiseart; ?>"></a><p class="spacer-80"></p></div>

<div class="container">
    <h2 class="fullsize-heading"></a><?php echo $title; ?></h2>
</div>

<div class="container">
    <div class="cards">

<?php

    $the_query = new WP_Query($query_args);

    // Posts wurden gefunden, was nun?
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts()) {
            $the_query->the_post();

            // Welches Land und welche Region soll angezeigt werden
            $taxanomyland = wp_get_post_terms(get_the_id(), 'land');            
            if($taxanomyland) { $cardland = $taxanomyland[0]->name; }

            $taxanomyregion = wp_get_post_terms(get_the_id(), 'region');
            if($taxanomyregion) { $cardregion = $taxanomyregion[0]->name; }
            

            // Thumbnail oder Hintergrundfarbe
            $image = get_the_post_thumbnail_url();

            // Link anzeigen
            $link = get_permalink();

            // Rendern, außer eine Bedingung ist erfüllt
            $dont_render = false;

            // Bedingung 1: Wenn nur eine spezielle Reiseart gesucht wird: Überspringen, falls diese Reise nicht dazugehört
            if ( $reiseart != "alle" ) {
                if ( $reiseart != get_field('art_der_reise') ) {
                    $dont_render = true;
                }
            }

            if ( $dont_render == false ) {

            ?>
                
                <div class="card" style="background-image: url('<?php echo $image; ?>');">
                    <div class="cardOverlay">            
                        <h2 class="cardTitle"><?php echo get_the_title(); ?></h2>
                        <h3 class="cardLand"><?php echo $cardland." - ".$cardregion; ?></h3>
                        <?php 
                        
                        $sprache = pll_current_language('name');

                        if ($sprache == "Deutsch") {
                            $more = "Mehr erfahren";
                        }
                        else{
                            $more = "Read more";
                        }

                        ?>
                        <div class="cardButton"><a href="<?php echo $link; ?>" class="btn"><?php echo $more; ?></a></div>
                    </div>
                </div>            

            <?php
            }
            $dont_render = false;

            }
        }
        
        wp_reset_postdata();

?>

    </div>
</div>
<div class="container">
    <div class="text">


<?php

$ret = ob_get_contents();
   ob_end_clean();
   return $ret;

   remove_filter('posts_orderby', 'custom_posts_orderby');

}
add_shortcode( 'reise', 'card_shortcode' );

?>