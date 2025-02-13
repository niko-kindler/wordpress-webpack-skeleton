<?php get_header();

while (have_posts()) {
    the_post(); 

$image = get_the_post_thumbnail_url();

if($image) {

?>

<div class="hero-small" style="background-image: url('<?php echo $image; ?>');"></div>

<div class="container margin">
    <h1 class="fullsize-heading">Familienzentrum Villa Lützow</h1>
</div>

<div class="container">
  <div class="text">
    <p>Willkommen im Familienzentrum Villa Lützow. Hier findest du die Veranstaltungen, die heute stattfinden:</p>
  </div>
</div>

<?php
}

// Veranstaltungen

// Deutschen Tag ausgeben
$day = date("D");

	if($day=="Mon") { $tag = "Montag"; }
	if($day=="Tue") { $tag = "Dienstag"; }
	if($day=="Wed") { $tag = "Mittwoch"; }
	if($day=="Thu") { $tag = "Donnerstag"; }
	if($day=="Fri") { $tag = "Freitag"; }
	if($day=="Sat") { $tag = "Montag"; }
	if($day=="Sun") { $tag = "Montag"; }

  $tag = "Freitag";

?>
<div class="container">
  <div class="text">
    <div class="spacer-20"></div>

    <div class="va_tag__heading-container">
      <div class="va_tag__heading-title"><?php echo $tag; ?></div>
    </div>

    <div class="va_tag__container">

      <?php
      // WP-Query für alle VA am Tag $tag
      $args = array(
        'post_type'      => 'veranstaltungen',
        'posts_per_page' => -1, // Alle Beiträge laden
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
        <div class="va_tag__card-thumbnail" style="background-image: url('<?php echo $va_thumbnail; ?>');">
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

<p class="center"><a href="/programm/" class="btn">Gesamtprogramm</a></p>

<div class="spacer-20"></div>

<div class="container">
  <div class="text">
        <?php the_content(); ?>
  </div>
</div>

<div class="spacer-40"></div>

<?php
//Kontaktformular

$form_versendet = false;

if(isset($_POST['name'])){

  $form_versendet = true;

  $name = (isset($_POST['name']))? sanitize_text_field($_POST['name']) : '';
  $email = (isset($_POST['email']))? sanitize_text_field($_POST['email']) : '';
  $telefon = (isset($_POST['telefon']))? sanitize_text_field($_POST['telefon']) : '';
  $nachricht = (isset($_POST['nachricht']))? sanitize_text_field($_POST['nachricht']) : '';
  $telefax = (isset($_POST['telefax']))? sanitize_text_field($_POST['telefax']) : '';

  if (!$telefax) {

  $empfaenger = get_option('admin_email');
  $betreff = "[".get_current_site_name()."] Nachricht von ". $name;
  
  $header  = "MIME-Version: 1.0\r\n";
  $header .= "Content-type: text/html; charset=utf-8\r\n";
  $header .= "From: ".get_current_site_name()." <info@mikro-webseite.de>";

  $text = "Nachricht auf Mikro-Webseite.de: Neue Nachricht auf Mikro-Webseite.de\r\n";
  $text .= "Name: ". $name ."<br>\r\n";
  $text .= "E-Mail: ". $email ."<br>\r\n";
  $text .= "Telefon: ". $telefon ."<br>\r\n";
  $text .= "Reise: ". $reise ."<br>\r\n";
  $text .= "Anreise / Abreise: ". $anreise ."\r\n";
  $text .= "Zusätzliche Informationen: ". $nachricht ."\r\n";

  mail($empfaenger,$betreff,$text,$header);
  
  $post_content = "Name: $name\n";
  $post_content .= "Email: $email\n";
  $post_content .= "Telefon: $telefon\n";
  $post_content .= "Anreise: $anreise\n";
  $post_content .= "Nachricht: $nachricht\n";
  if($reise) {
    $post_content .= "Reise: $reise\n";
  }

  $post_id = wp_insert_post(array(
    'post_title'    => $name, 
    'post_content'  => $post_content,
    'post_status'   => 'publish', 
    'post_author'   => 1, 
    'post_type'     => 'kontaktanfragen',));
  }
  
}

?>

<div class="container margin">
    <h2 class="fullsize-heading"><a name="kontaktformular"></a>Kontakt aufnehmen</h2>
</div>

<div class="container">
     
    <div class="text">

    <p class="spacer-20"></p>

    <p>Wir freuen uns auf Ihre Nachricht!</p>

    <p>Wir beantworten unsere Anfragen an Werktagen innerhalb von 48h. Bei dringenden Anfragen kontaktieren Sie uns bitte per WhatsApp oder Chat.</p>

    <p class="spacer-40"></p>

    <h2>Kontaktformular</h2>
 
    <?php

    if ($form_versendet == true) {

      echo "<p class=\"center\"><strong>Ihre Anfrage wurde gespeichert.</strong></p>";

    }

    else {

    ?>
    
    <form id="kontakt" method="POST" action="https://www.insidertrip.de/#kontaktformular">

       <input class="form-input" type="text" name="name" id="name" placeholder="Ihr Name" required>
    <input class="form-input" type="text" name="email" id="email" placeholder="Ihre E-Mail-Adresse" required>
    <input class="form-input" type="text" name="telefon" id="telefon" placeholder="Ihre Telefonnummer">
    <input class="form-inputs" type="text" name="telefax" id="telefax" placeholder="Ihre Faxnummer">

    <textarea class="form-message" name="nachricht" placeholder="Ihre Nachricht"></textarea>

    <p>Mit Absenden des Formulars stimmen Sie zu, dass wir Ihre Daten im Rahmen unserer Datenschutzerklärung speichern und verarbeiten.</p>

    <input type="submit" value="Abschicken" class="form__btn-submit"/>

    </form>

    <?php
      }
    ?>

    </div>
</div>

<div class="spacer-80"></div>

<div class="container margin">
    <h2 class="fullsize-heading"><?php echo get_field('copy_uberschrift'); ?>
</h2>
</div>

<div class="container margin">
    <div class="text">
        
        <?php echo get_field('copy'); ?>
    </div>
</div>


<?php
}

?>


<?php

get_footer(); ?>