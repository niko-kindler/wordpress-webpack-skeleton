<?php
/*
Plugin Name: Custom Post Types
Author: Nikolas Kindler
Version: 1.2
*/

// Veranstaltungen & Formübermittlungen

function mytheme_post_types(){

    register_post_type('veranstaltungen', array(
        'supports' => array(
            'title', 
            'editor',
            'thumbnail'
        ),
        'rewrite' => array(
          'slug' => 'veranstaltung')
        ,
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Veranstaltung',
            'add_new_item' => 'Neue Veranstaltung hinzufügen',
            'edit_item' => 'Veranstaltungen bearbeiten',
            'all_items' => 'Alle Veranstaltungen',
            'singular_name' => 'Veranstaltung'
        ),
        'menu_icon' => 'dashicons-star-filled'
    ));

   

    register_post_type('veranstaltungs_ort', array(
        'supports' => array(
            'title', 
            'editor',
            'thumbnail'
        ),
        'rewrite' => array(
          'slug' => 'veranstaltungs_ort')
        ,
        'has_archive' => false,
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Veranstaltungsort',
            'add_new_item' => 'Neuen Veranstaltungsort hinzufügen',
            'edit_item' => 'Veranstaltungsorte bearbeiten',
            'all_items' => 'Alle Veranstaltungsorte',
            'singular_name' => 'Veranstaltungsort'
        ),
        'menu_icon' => 'dashicons-admin-site'
    ));

    register_post_type('kontaktanfragen', array(
        'supports' => array(
            'title', 
            'editor',
            'thumbnail'
        ),
        'rewrite' => array(
          'slug' => 'ubermittlungen')
        ,
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Kontaktanfragen',
            'add_new_item' => 'Neu hinzufügen',
            'edit_item' => 'Bearbeiten',
            'all_items' => 'Alle Kontaktanfragen',
            'singular_name' => 'Kontaktanfragen'
        ),
        'menu_icon' => 'dashicons-media-document'
    ));

}

add_action( 'init', 'mytheme_post_types' );

// Veranstaltungen Taxanomy

function taxonomy_veranstaltungsarten() {

    $labels = array(
        'name'              => _x( 'Veranstaltungsarten', 'taxonomy general name' ),
        'singular_name'     => _x( 'Veranstaltungsart', 'taxonomy singular name' ),
        'search_items'      => __( 'Veranstaltungsarten durchsuchen' ),
        'all_items'         => __( 'Alle Veranstaltungsarten' ),
        'edit_item'         => __( 'Veranstaltungsart bearbeiten' ),
        'update_item'       => __( 'Veranstaltungsart aktualisieren' ),
        'add_new_item'      => __( 'Neues Veranstaltungsart hinzufügen' ),
        'new_item_name'     => __( 'Neuer Veranstaltungsartenname' ),
        'menu_name'         => __( 'Veranstaltungsarten' ),
    );

    $args = array(
        'hierarchical'      => true, // make it hierarchical (like categories)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'veranstaltungsarten' ],
    );
    register_taxonomy( 'veranstaltungsarten', [ 'veranstaltungen' ], $args );
}
add_action( 'init', 'taxonomy_veranstaltungsarten' );

function taxonomy_regions() {

    $labels = array(
        'name'              => _x( 'Regionen', 'taxonomy general name' ),
        'singular_name'     => _x( 'Region', 'taxonomy singular name' ),
        'search_items'      => __( 'Regionen durchsuchen' ),
        'all_items'         => __( 'Alle Regionen' ),
        'edit_item'         => __( 'Region bearbeiten' ),
        'update_item'       => __( 'Region aktualisieren' ),
        'add_new_item'      => __( 'Neues Region hinzufügen' ),
        'new_item_name'     => __( 'Neuer Regionsname' ),
        'menu_name'         => __( 'Regionen' ),
    );

    $args = array(
        'hierarchical'      => true, // make it hierarchical (like categories)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'region' ],
    );
    register_taxonomy( 'region', [ 'reisen' ], $args );
}
add_action( 'init', 'taxonomy_regions' );

// Admin Views anpasen
function add_veranstaltungen_columns($columns) {
    $columns['veranstaltungsart'] = 'Veranstaltungsart';
    $columns['modus'] = 'Modus';
    $columns['wochentag'] = 'Wochentag';
    $columns['uhrzeit_von'] = 'Uhrzeit von';
    return $columns;
}
add_filter('manage_veranstaltungen_posts_columns', 'add_veranstaltungen_columns');

// Füllen Sie die benutzerdefinierten Spalten mit Daten
function fill_veranstaltungen_columns($column, $post_id) {
    switch ($column) {
        case 'veranstaltungsart':
            $terms = get_the_terms($post_id, 'veranstaltungsart'); // Angenommen, 'veranstaltungsart' ist der Taxonomie-Name
            if ($terms && !is_wp_error($terms)) {
                $term_names = array();
                foreach ($terms as $term) {
                    $term_names[] = $term->name;
                }
                echo implode(', ', $term_names);
            }
            break;
        case 'modus':
            echo get_field('modus', $post_id)[0];
            break;
        case 'wochentag':
            echo get_field('wochentag', $post_id);
            break;
        case 'uhrzeit_von':
            echo get_field('uhrzeit_von', $post_id);
            break;
    }
}
add_action('manage_veranstaltungen_posts_custom_column', 'fill_veranstaltungen_columns', 10, 2);

// Machen Sie die Spalten sortierbar
function sortable_veranstaltungen_columns($columns) {
    $columns['modus'] = 'modus';
    $columns['wochentag'] = 'wochentag';
    $columns['uhrzeit_von'] = 'uhrzeit_von';
    return $columns;
}
add_filter('manage_edit-veranstaltungen_sortable_columns', 'sortable_veranstaltungen_columns');

?>