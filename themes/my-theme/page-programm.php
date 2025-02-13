<?php 
get_header(); 

$image = get_the_post_thumbnail_url();

if($image) {
?>

<div class="hero-small" style="background-image: url('<?php echo $image; ?>');"></div>

<?php
}
?>

<h1><?php the_title(); ?></h1>

<div class="calendar">
  <?php
  // Array der Tage
  $tage = array("Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag");

  $x = 0;
  
  // Für jeden Tag ein flexibles Element erzeugen
  while ( $x < count($tage) ) {
    $tag = $tage[$x];
    $x++;

  ?>
    <div class="va_tag">
      <div class="container">
        <div class="text">
          <div class="spacer-20" id="<?php echo $tag; ?>"></div>
          <div class="va_tag__heading-container">
            <div class="va_tag__heading-title" ><?php echo $tag; ?></a></div>
          </div>

          <div class="va_tag__container">
            <?php
            // WP-Query für alle VA am Tag $tag
            $args = array(
              'post_type'      => 'veranstaltungen',
              'posts_per_page' => -1,
              'meta_query'     => array(
                array(
                  'key'     => 'wochentag',
                  'value'   => $tag,
                  'compare' => '='
                )
              ),
              'orderby'  => 'meta_value',
              'meta_key' => 'uhrzeit_von',
              'order'    => 'ASC'
            );

            $veranstaltungen_query = new WP_Query( $args );

            if ( $veranstaltungen_query->have_posts() ) :
              while ( $veranstaltungen_query->have_posts() ) : $veranstaltungen_query->the_post();

                // ACF-Felder
                $uhrzeit_von = get_field('uhrzeit_von');
                $uhrzeit_bis = get_field('uhrzeit_bis');
                $veranstaltungsarten = get_field('veranstaltungsart');

                if ( has_post_thumbnail() ) {
                  $va_thumbnail = get_the_post_thumbnail_url();
                }

                $veranstaltungsort = get_field('veranstaltungsort');
                if ( $veranstaltungsort ) {
                  $farbe = get_field('veranstaltungsort-farbe', $veranstaltungsort->ID );
                }
            ?>

                <div class="va_tag__card" style="border: solid 5px <?php echo $farbe; ?>;">
                  <div class="va_tag__card-thumbnail-calendar" style="background-image: url('<?php echo $va_thumbnail; ?>');">
                  </div>

                  <!-- The VA Content -->
                  <div class="va-tag__content">
                    <div class="va-tag__content-preview">
                      <?php
                         // Wenn nicht jede Woche, zeige Rhythmus und nächste Daten:
                         $modus = get_field('modus')[0];

                         $start_date = get_field('erstes_va_datum');

                         if ( $start_date && $modus) {
                            $start = new DateTime( $start_date );
                            $today = new DateTime();
                                                    
                            if ( $modus == 'once' ) {
                                if ( $start < $today ) {
                                    echo "Einmalig, Termin am " . $start->format('d.m.') . " (bereits vorbei)";
                                } else {
                                    echo "Einmalig, Termin am " . $start->format('d.m.');
                                }
                            } else {
                                switch ( $modus ) {
                                    case 'weekly':
                                        $interval = new DateInterval('P1W');
                                        $text = "wöchentlich";
                                        break;
                                    case 'biweekly':
                                        $interval = new DateInterval('P2W');
                                        $text = "alle zwei Wochen";
                                        break;
                                    case 'monthly':
                                        $interval = new DateInterval('P1M');
                                        $text = "monatlich";
                                        break;
                                    default:
                                        $interval = null;
                                        $text = "";
                                        break;
                                }
                                
                                if ( $interval ) {
                                    // Falls das Startdatum bereits in der Vergangenheit liegt, finde den nächsten Termin
                                    while ( $start < $today ) {
                                        $start->add( $interval );
                                    }

                                    if($start == $today) {
                                        echo $text . ", nächster Termin <strong>heute</strong>!";
                                    }

                                    else {
                                        echo $text . ", nächster Termin am " . $start->format('d.m.');
                                    }
                                }
                            }
                        }

                      ?>
                      <p class="va-tag__buttons">
                        <button class="btn-load-more">+</button>
                        <a class="btn-signup" href="<?php echo get_permalink(); ?>">Infos &amp; Anmelden</a>
                      </p>
                    </div>

                    <div class="va-tag__content-full" style="display: none;">
                      <div class="va_tag__card-heading"><?php the_title(); ?></div>
                      <div class="va_tag__card-meta">
                        <p class="va_tag__card-location">
                          <span class="dashicons dashicons-location"></span>
                          <?php echo $veranstaltungsort->post_title; ?>
                        </p>
                        <p>
                          <span class="dashicons dashicons-clock"></span>
                          <?php echo esc_html( $uhrzeit_von ); ?> bis <?php echo esc_html( $uhrzeit_bis ); ?> Uhr
                        </p>
                        <?php if ( $veranstaltungsarten ) { ?>
                          <p class="va_tag__card-tags">
                            <?php
                            foreach ( $veranstaltungsarten as $veranstaltungsart ) {
                              echo '<span>' . $veranstaltungsart->name . '</span>';
                            }
                            ?>
                          </p>
                        <?php } ?>
                      </div>
                      <?php the_content(); ?>
                      <p class="va-tag__buttons">
                        <button class="btn-show-less">-</button>
                        <a class="btn-signup" href="<?php echo get_permalink(); ?>">Infos &amp; Anmelden</a>
                      </p>
                    </div><!-- End .va-tag__content-full -->
                  </div><!-- End .va-tag__content -->
                </div><!-- End .va_tag__card -->

                <div class="spacer-40"></div>

            <?php
              endwhile;
              wp_reset_postdata();
            else :
            ?>
              <p>Keine Veranstaltungen gefunden.</p>
            <?php endif; ?>
          </div><!-- End .va_tag__container -->
        </div><!-- End .text -->
      </div><!-- End .container -->
    </div><!-- End .va_tag -->
  <?php } ?>
</div><!-- End .calendar -->


<div class="container">
    <div class="text"><?php the_content(); ?></div>
</div>

<div class="spacer-80"></div>

<?php get_footer(); ?>