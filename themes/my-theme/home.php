<?php

get_header();
echo 'Hello from home.php';

while (have_posts()) {
    the_post();

    if (has_excerpt()) {
        $weitereBeiträge_content = get_the_excerpt();
    } else {
        $weitereBeiträge_content = wp_trim_words(get_the_content(), 18);
    }

    $postBild = get_the_post_thumbnail_url();

    if ($postBild) { ?>

<div style="background-image: url(<?php echo $postBild; ?>);">
  <a href="<?php echo the_permalink(); ?>"></a>
</div>

<?php
    } ?>

<div>
  <p><?php the_title(); ?></p>
  <p><?php the_time('d.m.Y'); ?> -
    <?php the_author(); ?>
  </p>
  <p><?php echo $weitereBeiträge_content; ?></p>
  <p><a href="<?php echo the_permalink(); ?>">Artikel lesen</a></p>
</div>

<?php
} ?>


<?php echo paginate_links();

get_footer(); ?>