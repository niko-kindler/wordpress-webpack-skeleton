<?php 
get_header(); 

$image = get_the_post_thumbnail_url();

if($image) {
?>

<div class="hero-small" style="background-image: url('<?php echo $image; ?>');"></div>

<?php
}
?>

<?php
  $title = "";
  $ferienmodus = get_field('ferienmodus',55);

  if (isset($_GET["p"])) {
    if($_GET["p"]=="gesamtprogramm") { $title = "Gesamtprogramm"; }
    if($_GET["p"]=="bildungsangebote") { $title = "Bildungsangebote"; }
    if($_GET["p"]=="beratungsangebote") { $title = "Beratungsangebote"; }
    if($_GET["p"]=="begegnungsangebote") { $title = "Begegnungsangebote"; }
  } 
  else { 
    if ($ferienmodus == "ein") {
      $title = "Ferienprogramm";
    }
  }
?>

<h1><?php if ($title) { echo $title; } else { the_title(); } ?></h1>

<div class="container">
<div class="text">
  
<?php
if ($ferienmodus == "ein") {
  echo get_field('ferienprogramm_text', 55);
}
elseif ($title == "Bildungsangebote") {
  echo get_field('bildungsangebote', 55);
}
elseif ($title == "Beratungsangebote") {
  echo get_field('beratungsangebote', 55);
}
elseif ($title == "Begegnungsangebote") {
  echo get_field('begegnungsangebote', 55);
}
else {
  echo get_field('gesamtprogramm', 55);
}

?>
</div>
</div>

<ul class="filter">
  <li><span class="dashicons dashicons-filter"></span></li>
  
  <?php 
  
  if ($ferienmodus == "ein") { 
  ?>
  
  <li><a href="/programm/" class="<?php if (!isset($_GET["p"])) { echo "active"; } ?>">Ferienprogramm</a></li>
  <li><a href="/programm/?p=gesamtprogramm" class="<?php if ($_GET["p"]=="gesamtprogramm") { echo "active"; } ?>">Gesamtprogramm</a></li>
  
  <?php
  }
  else {
  ?>

  <li><a href="/programm/" class="<?php if (!isset($_GET["p"])) { echo "active"; } ?>">Gesamtprogramm</a></li>
  
  <?php
  }
  ?>
  
  <li><a href="/programm/?p=bildungsangebote" class="<?php if(isset($_GET["p"])) { if($_GET["p"]=="bildungsangebote") { echo "active"; }} ?>">Bildungsangebote</a></li>
  <li><a href="/programm/?p=beratungsangebote" class="<?php if(isset($_GET["p"])) { if($_GET["p"]=="beratungsangebote") { echo "active"; }} ?>">Beratungsangebote</a></li>
  <li><a href="/programm/?p=begegnungsangebote" class="<?php if(isset($_GET["p"])) { if($_GET["p"]=="begegnungsangebote") { echo "active"; }} ?>">Begegnungsangebote</a></li>
</ul>

<div class="calendar">
  <?php
  // Array der Tage
  $tage = array("Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag");

  foreach ($tage as $tag) {
    // Set the $tag and $kategorie variable for the template part
    set_query_var('tag', $tag);
    get_template_part('template-parts/content', 'va_tag', array ('kategorie' => $title));
  }
  ?>
</div><!-- End .calendar -->


<div class="container">
    <div class="text"><?php the_content(); ?></div>
</div>

<div class="spacer-80"></div>

<?php get_footer(); ?>