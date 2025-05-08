<?php get_header();

// Familienzentrum Villa Lützow

while (have_posts()) {
    the_post(); 

$image = get_the_post_thumbnail_url();

if($image) {

?>

<div class="hero-small" style="background-image: url('<?php echo $image; ?>');"></div>

<?php
}
?>

<div class="container margin center">
  <div class="text">    
    <h1><?php echo get_bloginfo('name'); ?> </h1>
  </div>
</div>

<div class="container">
  <div class="text">
    <p>Willkommen im <?php echo get_bloginfo('name'); ?>. Mehr über uns findest du <a href="#infos">hier</a>. Hier findest du die Veranstaltungen, die heute stattfinden:</p>
  </div>
</div>

<div class="container">
<div class="text">
<?php
$ferienmodus = get_field('ferienmodus',55);

if ($ferienmodus == "ein") {
  echo get_field('ferienprogramm_text', 55);
}
?>
</div>
</div>


<?php
// Deutschen Tag ausgeben
$day = date("D");

$tag_mapping = array(
  'Mon' => 'Montag',
  'Tue' => 'Dienstag',
  'Wed' => 'Mittwoch',
  'Thu' => 'Donnerstag',
  'Fri' => 'Freitag',
  'Sat' => 'Montag',
  'Sun' => 'Sonntag'
);

$tag = isset($tag_mapping[$day]) ? $tag_mapping[$day] : 'Montag';

// Set the $tag variable for the template part
set_query_var('tag', $tag);
get_template_part('template-parts/content', 'va_tag');

?>

<p class="center" id="infos"><a href="/programm/" class="btn">Gesamtprogramm</a></p>

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
  $header .= "From: ".get_current_site_name()." <wordpress@familienzentrum-villaluetzow.de>";

  $text = "Neue Nachricht\r\n";
  $text .= "Name: ". $name ."<br>\r\n";
  $text .= "E-Mail: ". $email ."<br>\r\n";
  $text .= "Telefon: ". $telefon ."<br>\r\n";
  $text .= "Zusätzliche Informationen: ". $nachricht ."\r\n";

  mail($empfaenger,$betreff,$text,$header);
  
  $post_content = "Name: $name\n";
  $post_content .= "Email: $email\n";
  $post_content .= "Telefon: $telefon\n";
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

    <p>Wir freuen uns auf Eure Nachrichten!</p>

    <p class="spacer-40"></p>

    <h2>Kontaktformular</h2>
 
    <?php

    if ($form_versendet == true) {

      echo "<p class=\"center\"><strong>Ihre Anfrage wurde gespeichert.</strong></p>";

    }

    else {

    ?>
    
    <form id="kontakt" method="POST" action="">

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