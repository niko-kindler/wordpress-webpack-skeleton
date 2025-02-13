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

// Button Liste rendern
?>

<div class="container">
    <div class="text">
      <h2>Willkommen in unserem Familienzentrum. Das ist heute bei uns los:</h2>

<div>
  <ul class="va_tag">
    <li><button class="va_button <?php if($tag=="Montag") { echo "today"; } ?>" onclick="openTag('Montag')"><?php if($tag=="Montag") { echo "<strong>$tag</strong>"; } else { echo "Montag"; } ?></button></li>
    <li><button class="va_button <?php if($tag=="Dienstag") { echo "today"; } ?>" onclick="openTag('Dienstag')"><?php if($tag=="Dienstag") { echo "<strong>$tag</strong>"; } else { echo "Dienstag"; } ?></button></li>
    <li><button class="va_button <?php if($tag=="Mittwoch") { echo "today"; } ?>" onclick="openTag('Mittwoch')"><?php if($tag=="Mittwoch") { echo "<strong>$tag</strong>"; } else { echo "Mittwoch"; } ?></button></li>
    <li><button class="va_button <?php if($tag=="Donnerstag") { echo "today"; } ?>" onclick="openTag('Donnerstag')"><?php if($tag=="Donnerstag") { echo "<strong>$tag</strong>"; } else { echo "Donnerstag"; } ?></button></li>
    <li><button class="va_button <?php if($tag=="Freitag") { echo "today"; } ?>" onclick="openTag('Freitag')"><?php if($tag=="Freitag") { echo "<strong>$tag</strong>"; } else { echo "Freitag"; } ?></button></li>
  </ul>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    const currentTag = "<?php echo $tag; ?>";

    if (currentTag) {
      initializeTag(currentTag);
    }
  });
</script>

<div id="Montag" class="tag">
  <h2>Montag</h2>
  <span onclick="this.parentElement.style.display='none'" class="right">Schließen</span>
  
  <div class="va_kachel">
    <p class="va_ueberschrift"><strong>Veranstaltungstitel</strong></p>
    <p class="va_kategorie">Eltern- und Familienangebote</p>
    <p class="va_datetime">Jeden Montag, 10-12 Uhr</p>
    <p class="va_icon"><img src="C:\Users\nikol\Downloads\family.png" width="25px" height="25px" /></p>
    <img src="C:\Users\nikol\Local Sites\kidz-ev\app\public\wp-content\themes\my-theme\src\images\Styling\IMG_1591-1-e1736427706166.jpeg" />
    <button class="va_mehrinformation">Mehr Informationen</button>
  </div>

  <div class="va_kachel">
    <p class="va_ueberschrift"><strong>Veranstaltungstitel</strong></p>
    <p class="va_kategorie">Eltern- und Familienangebote</p>
    <p class="va_datetime">Jeden Montag, 10-12 Uhr</p>
    <p class="va_icon"><img src="C:\Users\nikol\Downloads\family.png" width="25px" height="25px" /></p>
    <img src="C:\Users\nikol\Local Sites\kidz-ev\app\public\wp-content\themes\my-theme\src\images\Styling\IMG_1591-1-e1736427706166.jpeg" />
    <button class="va_mehrinformation">Mehr Informationen</button>
  </div>

</div>

<div id="Donnerstag" class="tag">
<h2>Donnerstag</h2>
<span onclick="this.parentElement.style.display='none'" class="right">Schließen</span>
  
  <div>
    <h2>Für heute ist kein Programm geplant.</h2>
  </div>

</div>

<div id="Freitag" class="tag">
<h2>Freitag</h2>
<span onclick="this.parentElement.style.display='none'" class="right">Schließen</span>
  
  <div class="va_kachel">
    <p class="va_ueberschrift"><strong>Veranstaltungstitel</strong></p>
    <p class="va_kategorie">Eltern- und Familienangebote</p>
    <p class="va_datetime">Jeden Montag, 10-12 Uhr</p>
    <p class="va_icon"><img src="C:\Users\nikol\Downloads\family.png" width="25px" height="25px" /></p>
    <img src="C:\Users\nikol\Local Sites\kidz-ev\app\public\wp-content\themes\my-theme\src\images\Styling\IMG_1591-1-e1736427706166.jpeg" />
    <button class="va_mehrinformation">Mehr Informationen</button>
  </div>

  <div class="va_kachel">
    <p class="va_ueberschrift"><strong>Veranstaltungstitel</strong></p>
    <p class="va_kategorie">Eltern- und Familienangebote</p>
    <p class="va_datetime">Jeden Montag, 10-12 Uhr</p>
    <p class="va_icon"><img src="C:\Users\nikol\Downloads\family.png" width="25px" height="25px" /></p>
    <img src="C:\Users\nikol\Local Sites\kidz-ev\app\public\wp-content\themes\my-theme\src\images\Styling\IMG_1591-1-e1736427706166.jpeg" />
    <button class="va_mehrinformation">Mehr Informationen</button>
  </div>

</div>

  </div>
</div>

<div class="container">
  <div class="text center">
     <a class="btn" href="#">Vollständiges Programm</a>
  </div>
</div>

<div class="spacer-80"></div>

<?php
// Inhalt
?>

<div class="container">
  <div class="text">
        <?php the_content(); ?>
  </div>
</div>

<div class="spacer-80"></div>

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

    <p class="spacer-40"></p>

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