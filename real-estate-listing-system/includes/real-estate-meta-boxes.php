<?php
function rels_add_property_meta_boxes() {
    add_meta_box('property_details', 'Property Details', 'rels_property_meta_box_callback', 'property', 'normal', 'high');
}

add_action('add_meta_boxes', 'rels_add_property_meta_boxes');

function rels_property_meta_box_callback($post) {
    // Retrieve and display meta fields like price, location, bedrooms, etc.
    $price = get_post_meta($post->ID, '_price', true);
    $location = get_post_meta($post->ID, '_location', true);
    echo '<label for="price">Price:</label>';
    echo '<input type="text" name="price" id="price" value="' . esc_attr($price) . '" />';
    echo '<br><label for="location">Location:</label>';
    echo '<input type="text" name="location" id="location" value="' . esc_attr($location) . '" />';
}

function rels_save_property_meta_boxes($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['price'])) {
        update_post_meta($post_id, '_price', sanitize_text_field($_POST['price']));
    }
    if (isset($_POST['location'])) {
        update_post_meta($post_id, '_location', sanitize_text_field($_POST['location']));
    }
}

add_action('save_post', 'rels_save_property_meta_boxes');
