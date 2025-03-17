<?php 
get_header(); 

$image = get_the_post_thumbnail_url();
?>

<h1><?php the_title(); ?></h1>

<div class="container">
    <div class="text">
        <?php if ($image) { echo "<img src=\"". $image ."\" width=\"100%\" />"; } ?>
        <p>&nbsp;</p>
        <?php the_content(); ?>
        <p>&nbsp;</p>
        <h3>Anmeldeinformationen</h3>
        <?php echo the_field('anmeldeinformationen'); ?>
    </div>
</div>

<div class="spacer-80"></div>

<?php

$trigger_contact = get_field('kontaktformular_anzeigen');

if ($trigger_contact == "ja") {
    
//Kontaktformular

$form_versendet = false;

if(isset($_POST['name'])){

  $form_versendet = true;

  $name = (isset($_POST['name']))? sanitize_text_field($_POST['name']) : '';
  $veranstaltung = (isset($_POST['veranstaltung']))? sanitize_text_field($_POST['veranstaltung']) : '';
  $email = (isset($_POST['email']))? sanitize_text_field($_POST['email']) : '';
  $telefon = (isset($_POST['telefon']))? sanitize_text_field($_POST['telefon']) : '';
  $nachricht = (isset($_POST['nachricht']))? sanitize_text_field($_POST['nachricht']) : '';
  $telefax = (isset($_POST['telefax']))? sanitize_text_field($_POST['telefax']) : '';

  if (!$telefax) {

  $site_name = get_option('blogname');
  $empfaenger = get_option('admin_email');
  $betreff = "[".$site_name."] Nachricht von ". $name;
  
  $header  = "MIME-Version: 1.0\r\n";
  $header .= "Content-type: text/html; charset=utf-8\r\n";
  $header .= "From: ".$site_name." <wordpress@familienzentrum-villaluetzow.de>";

  $text = "Nachricht auf ".$site_name.": Neue Nachricht\r\n";
  $text .= "Name: ". $name ."<br>\r\n";
  $text .= "E-Mail: ". $email ."<br>\r\n";
  $text .= "Telefon: ". $telefon ."<br>\r\n";
  if($veranstaltung) {
    $post_content .= "Veranstaltung: $veranstaltung\n";
  }
  $text .= "Zusätzliche Informationen: ". $nachricht ."\r\n";
  
  mail($empfaenger,$betreff,$text,$header);
  
  $post_content = "Name: $name\n";
  $post_content .= "Email: $email\n";
  $post_content .= "Telefon: $telefon\n";
  $post_content .= "Anreise: $anreise\n";
  if($veranstaltung) {
    $post_content .= "Veranstaltung: $veranstaltung\n";
  }
  $post_content .= "Nachricht: $nachricht\n";
  
  $post_id = wp_insert_post(array(
    'post_title'    => $name, 
    'post_content'  => $post_content,
    'post_status'   => 'publish', 
    'post_author'   => 1, 
    'post_type'     => 'kontaktanfragen',));
  }
  
}

?>

<div class="container">
     
    <div class="text">

    <h3>Schreib uns eine Nachricht</h3>
 
    <?php

    if ($form_versendet == true) {

      echo "<p class=\"center\"><strong>Ihre Anfrage wurde gespeichert.</strong></p>";

    }

    else {

    ?>
    
    <form id="kontakt" method="POST" action="">
    
    <input class="form-input" type="text" name="veranstaltung" id="veranstaltung" value="<?php echo the_title(); ?>">
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

<?php

}

?>

<div class="spacer-80"></div>

<?php get_footer(); ?>