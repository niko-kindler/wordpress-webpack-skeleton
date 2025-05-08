<?php

get_header();

?>

<h1>Neugikeiten</h1>

<?php

wp_reset_postdata();

while (have_posts()) {
    the_post();

    if (has_excerpt()) {
        $weitereBeiträge_content = get_the_excerpt();
    } else {
        $weitereBeiträge_content = wp_trim_words(get_the_content(), 18);
    }

    $postBild = get_the_post_thumbnail_url();

    if ($postBild) { ?>


<div class="container">
  <div class="text news">
    <div class="news-img" style="background-image: url(<?php echo $postBild; ?>);">
      <a href="<?php echo the_permalink(); ?>"></a>
    </div>
  </div>
</div>

<?php
    } ?>

<div class="container">
    <div class="text news">
      <h3><?php the_title(); ?></h3>
      <p><?php the_time('d.m.Y'); ?> - <?php the_author(); ?></p>
      <p><?php echo $weitereBeiträge_content; ?></p>
      <p><a class="btn"  href="<?php echo the_permalink(); ?>">Artikel lesen</a></p>
    </div>
</div>

<?php
} ?>

<?php echo paginate_links();

get_footer(); ?>