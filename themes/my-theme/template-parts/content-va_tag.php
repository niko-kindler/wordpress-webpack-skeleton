<div class="va_tag">
  <div class="container">
    <div class="text">
      <div class="spacer-20" id="<?php echo esc_attr($tag); ?>"></div>
      <div class="va_tag__heading-container">
        <div class="va_tag__heading-title"><?php echo esc_html($tag); ?></div>
      </div>

      <div class="va_tag__container">

        <?php 
          if(isset($args['kategorie'])) {
            $kategorie = $args['kategorie'];
          }
          if (isset($kategorie)){
            if ($kategorie == "Gesamtprogramm") {
              unset($kategorie);
          }}
        ?>

        <?php
        // Objekt mit Feiertagen holen

        $feiertage_json = get_field('feiertage', 55);
        
        if($feiertage_json != "") {
          $feiertage = json_decode($feiertage_json, true);
        }

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
          'tax_query' => (!empty($kategorie)) ? array(
            array(
              'taxonomy' => 'veranstaltungsarten',
              'field'    => 'name',
              'terms'    => $kategorie,
            )
          ) : array(), // Falls keine Kategorie gesetzt ist, bleibt tax_query leer
          'orderby'  => 'meta_value',
          'meta_key' => 'uhrzeit_von',
          'order'    => 'ASC'
        );

        $veranstaltungen_query = new WP_Query($args);

        if ($veranstaltungen_query->have_posts()) :
          while ($veranstaltungen_query->have_posts()) : $veranstaltungen_query->the_post();
          
          $mode_message = "";

          // Findet statt?
          $findet_nicht_statt = get_field('findet_nicht_statt');
          $ferienmodus = get_field('ferienmodus',55);
          $strike = "";
          $strike_end = "";
          $begruendung = "";
          $black_white = "";

          // ACF-Felder
          $uhrzeit_von = get_field('uhrzeit_von');
          $uhrzeit_bis = get_field('uhrzeit_bis');
          $veranstaltungsarten = get_field('veranstaltungsart');

          // Wenn einzelner Termin nicht stattfinden kann
          if ($findet_nicht_statt == true) {
            $strike = "<span style=\"text-decoration: line-through;\">";
            $strike_end = "</span>";
            $begruendung = "<span style=\"color: red; font-weight: bold;\">". get_field('findet_nicht_statt_weil') . "</span>";
            $black_white = " filter: grayscale(100%);";
          }

          // Wenn Ferienprogramm
          
          if ($ferienmodus == "ein") {
            $findet_in_ferien_statt = "";
            if(is_array($veranstaltungsarten)) {

              foreach ($veranstaltungsarten as $veranstaltungsart) {
                if ($veranstaltungsart->term_id == 25) {
                  $findet_in_ferien_statt = "JA";
                }
              }

              if($findet_in_ferien_statt != "JA") {
                $strike = "<span style=\"text-decoration: line-through;\">";
                $strike_end = "</span>";
                $begruendung = "<span style=\"color: red; font-weight: bold;\">Findet nicht in den Ferien statt!</span>";
                $black_white = " filter: grayscale(100%);";
              }
            } 
            
          }
          
          // Wenn nicht jede Woche, zeige Rhythmus und nächste Daten:
          $modus = get_field('modus')[0];

          $va_tag = get_field('wochentag');
          
          $start_date = get_field('erstes_va_datum');
          $uhrzeit_bis = get_field('uhrzeit_bis');
  
          if ($start_date && $modus) {
            if (!$uhrzeit_bis) {
              $uhrzeit_bis = '23:59';  // Fallback, wenn keine Uhrzeit vorhanden
            }

            $start = new DateTime($start_date . ' ' . $uhrzeit_bis);
            $today = new DateTime();
          
            if ($modus == 'once') {
              if ($start < $today) {
                $mode_message = "Einmalig, Termin am " . $start->format('d.m.') . " (bereits vorbei)";
              } else {
                $mode_message = "Einmalig, Termin am " . $start->format('d.m.');
              }
            } else {
              switch ($modus) {
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
                case '24':
                  $text = "jeden 2. und 4. Donnerstag im Monat";
                  
                  function getNthThursday($year, $month, $nth) {
                      $date = new DateTime("first day of $year-$month");
                      while ($date->format('w') != 4) { // 4 = Donnerstag
                          $date->modify('+1 day');
                      }
                      $date->modify('+' . (($nth - 1) * 7) . ' days');
                      return $date;
                  }
              
                  $current_month = (int) $today->format('m');
                  $current_year = (int) $today->format('Y');
              
                  $second_thursday = getNthThursday($current_year, $current_month, 2);
                  $fourth_thursday = getNthThursday($current_year, $current_month, 4);
              
                  if ($second_thursday >= $today) {
                      $next_date = $second_thursday;
                  } elseif ($fourth_thursday >= $today) {
                      $next_date = $fourth_thursday;
                  } else {
                      // Falls beide Termine vorbei sind, nimm den 2. Donnerstag im nächsten Monat
                      $next_month = ($current_month == 12) ? 1 : $current_month + 1;
                      $next_year = ($current_month == 12) ? $current_year + 1 : $current_year;
                      $next_date = getNthThursday($next_year, $next_month, 2);
                  }
                  $interval = false;
                  $mode_message = "<span class=\"va_tag__content-next\">" . $text . ", nächster Termin am <strong>" . $strike ."". $va_tag ." ".  $next_date->format('d.m.') ."". $strike_end ." ". $begruendung . "</strong></span>";
                  break;
                  case 'interval':
                    $text = "nur von Oktober bis März";
                
                    $current_month = (int) $today->format('m');
                    $current_year = (int) $today->format('Y');
                
                    if ($current_month >= 10 || $current_month <= 3) {
                        // Aktuell in der erlaubten Zeitspanne, nächster Termin ist das ursprüngliche Startdatum
                        $next_date = $start;
                        
                        // Falls das Startdatum bereits vergangen ist, einfach eine Woche weiter rechnen
                        while ($next_date < $today) {
                            $next_date->add(new DateInterval('P1W')); // Falls es ein wöchentliches Event ist, hier anpassen
                        }
                    } else {
                        // Suche den nächsten Oktober als Startpunkt
                        $next_year = ($current_month >= 4) ? $current_year + 1 : $current_year;
                        $next_date = new DateTime("first day of October $next_year");
                    }
                
                    $mode_message = "<span class=\"va_tag__content-next\">" . $text . ", nächster Termin am <strong>" . $strike ."". $va_tag ." ". $next_date->format('d.m.') ."". $strike_end ." ". $begruendung . "</strong></span>";
                break;
                default:
                  $interval = null;
                  $text = "";
                  break;
              }
          
              if ($interval) {
                // Falls das Startdatum bereits in der Vergangenheit liegt, finde den nächsten Termin
                while ($start < $today) {
                  $start->add($interval);
                }
          
                $mode_message = "<span class=\"va_tag__content-next\">" . $text . ", nächster Termin am <strong>" . $strike ."". $va_tag .", ".  $start->format('d.m.') ."". $strike_end ." ". $begruendung . "</strong></span>";
              }
            }
          }

            if (has_post_thumbnail()) {
              $va_thumbnail = get_the_post_thumbnail_url();
            }

            $veranstaltungsort = get_field('veranstaltungsort');
            if ($veranstaltungsort) {
              $farbe = get_field('veranstaltungsort-farbe', $veranstaltungsort->ID);
            }
        ?>

            <div class="va_tag__card" style="border: solid 5px <?php echo esc_attr($farbe); ?>;">
              
                <?php                
                  if (isset($va_thumbnail)){
                    if ($va_thumbnail) {
                    ?>
                    <div class="va_tag__card-thumbnail" style="background-image: url('<?php echo esc_url($va_thumbnail); ?>'); <?php echo $black_white; ?>">
                      <a href="<?php echo esc_url(get_permalink()); ?>" style="display:block;height:100%;width:100%;"></a>
                    </div>
                    <?php
                    }
                    else {
                      ?>
                      <div class="va_tag__card-thumbnail">
                        <a href="<?php echo esc_url(get_permalink()); ?>" style="text-align: center;"><h2 style="color:rgb(221, 221, 221);";><?php echo $strike; the_title(); echo $strike_end; ?></h2></a>
                      </div>
                      <?php
                    }
                  }
                ?>
                
              
              <div class="va_tag__card-heading"><?php the_title(); ?></div>
                  <div class="va_tag__card-meta">
                    <p class="va_tag__card-location">
                      <span class="dashicons dashicons-location"></span>
                      <?php echo esc_html($veranstaltungsort->post_title); ?>
                    </p>
                    <p>
                      <span class="dashicons dashicons-clock"></span>
                      <?php echo esc_html($uhrzeit_von); ?> bis <?php echo esc_html($uhrzeit_bis); ?> Uhr | <?php echo $mode_message; ?>
                    </p>
                    <?php // if ($veranstaltungsarten) { ?>
                      <!--<p class="va_tag__card-tags">
                        <?php
                        // foreach ($veranstaltungsarten as $veranstaltungsart) {
                          // echo '<span>' . esc_html($veranstaltungsart->name) . '</span>';
                        // }
                        ?>
                      </p>-->
                    <?php // } ?>
                  </div>
              <!-- The VA Content -->
              <div class="va-tag__content">
                <div class="va-tag__content-preview">
                  <p class="va-tag__buttons">
                    <button class="btn-load-more">+</button>
                    <a class="btn-signup" href="<?php echo esc_url(get_permalink()); ?>">Infos &amp; Anmelden</a>
                  </p>
                </div>

                <div class="va-tag__content-full" style="display: none;">                  
                  <?php the_content(); ?>
                  <p class="va-tag__buttons">
                    <button class="btn-show-less">-</button>
                    <a class="btn-signup" href="<?php echo esc_url(get_permalink()); ?>">Infos &amp; Anmelden</a>
                  </p>
                </div><!-- End .va-tag__content-full -->
              </div><!-- End .va-tag__content -->
            </div><!-- End .va_tag__card -->

            <div class="spacer-40"></div>

        <?php
            $va_thumbnail = "";
          endwhile;
          wp_reset_postdata();
        else :
        ?>
          <p><?php echo get_field('text_fur_keine_veranstaltungen',55); ?></p>
        <?php endif; ?>
      </div><!-- End .va_tag__container -->
    </div><!-- End .text -->
  </div><!-- End .container -->
</div><!-- End .va_tag -->