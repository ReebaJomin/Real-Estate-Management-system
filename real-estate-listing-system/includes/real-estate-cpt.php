<?php
function rels_register_property_cpt() {
    $labels = array(
        'name' => 'Properties',
        'singular_name' => 'Property',
        'menu_name' => 'Properties',
        'name_admin_bar' => 'Property',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'properties'),
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'capability_type' => 'post',
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-home',
    );

    register_post_type('property', $args);
}

add_action('init', 'rels_register_property_cpt');
