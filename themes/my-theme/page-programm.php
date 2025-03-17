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
  if (isset($_GET["p"])) {
    if($_GET["p"]=="bildungsangebote") { $title = "Bildungsangebote"; }
    if($_GET["p"]=="beratungsangebote") { $title = "Beratungsangebote"; }
    if($_GET["p"]=="begegnungsangebote") { $title = "Begegnungsangebote"; }
  } 
?>

<h1><?php if ($title) { echo $title; } else { the_title(); } ?></h1>

<ul class="filter">
  <li><span class="dashicons dashicons-filter"></span></li>
  <li><a href="/programm/" class="<?php if (!isset($_GET["p"])) { echo "active"; } ?>">Gesamtprogramm</a></li>
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