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

<div class="container">
    <div class="text"><?php the_content(); ?></div>
</div>

<div class="spacer-80"></div>

<?php get_footer(); ?>