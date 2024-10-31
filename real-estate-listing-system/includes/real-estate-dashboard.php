<?php
// Ensure this file is not accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Shortcode for Property Owner Dashboard
function rels_owner_dashboard() {
    // Check if the user is logged in and has the 'property_owner' role
    if (is_user_logged_in() && current_user_can('property_owner')) {
        $current_user_id = get_current_user_id();

        // Query properties belonging to the logged-in user
        $args = array(
            'post_type' => 'property',
            'posts_per_page' => -1,  // Show all properties
            'author' => $current_user_id,  // Filter by the current user (property owner)
        );

        $query = new WP_Query($args);

        ob_start();

        // Display the list of properties
        if ($query->have_posts()) {
            echo '<h3>Your Property Listings</h3>';
            echo '<ul class="property-listings">';
            while ($query->have_posts()) {
                $query->the_post();
                $property_id = get_the_ID();
                $status = get_post_meta($property_id, '_status', true);

                // Display property details and management options
                echo '<li>';
                the_title('<h4>', '</h4>');
                echo '<p>Price: $' . get_post_meta($property_id, '_price', true) . '</p>';
                echo '<p>Status: ' . ($status ? $status : 'Available') . '</p>';

                // Edit, Delete, or Mark as Sold/Rented buttons
                echo '<a href="' . get_edit_post_link($property_id) . '">Edit</a> | ';
                echo '<a href="' . esc_url(add_query_arg(array('delete_property' => $property_id))) . '" onclick="return confirm(\'Are you sure?\')">Delete</a> | ';

                if ($status !== 'Sold' && $status !== 'Rented') {
                    echo '<a href="' . esc_url(add_query_arg(array('mark_sold' => $property_id))) . '">Mark as Sold</a> | ';
                    echo '<a href="' . esc_url(add_query_arg(array('mark_rented' => $property_id))) . '">Mark as Rented</a>';
                }
                
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No properties found.</p>';
        }

        wp_reset_postdata();

        return ob_get_clean();
    } else {
        return '<p>You need to be logged in as a Property Owner to view this page.</p>';
    }
}
add_shortcode('property_owner_dashboard', 'rels_owner_dashboard');
// Handle Property Deletion
function rels_handle_property_actions() {
    if (is_user_logged_in() && current_user_can('property_owner')) {
        $current_user_id = get_current_user_id();

        // Check if the "delete_property" URL parameter is set
        if (isset($_GET['delete_property'])) {
            $property_id = intval($_GET['delete_property']);

            // Ensure the property belongs to the current user
            if (get_post_field('post_author', $property_id) == $current_user_id) {
                wp_delete_post($property_id);
                wp_redirect(remove_query_arg('delete_property'));
                exit;
            }
        }

        // Handle Mark as Sold
        if (isset($_GET['mark_sold'])) {
            $property_id = intval($_GET['mark_sold']);
            if (get_post_field('post_author', $property_id) == $current_user_id) {
                update_post_meta($property_id, '_status', 'Sold');
                wp_redirect(remove_query_arg('mark_sold'));
                exit;
            }
        }

        // Handle Mark as Rented
        if (isset($_GET['mark_rented'])) {
            $property_id = intval($_GET['mark_rented']);
            if (get_post_field('post_author', $property_id) == $current_user_id) {
                update_post_meta($property_id, '_status', 'Rented');
                wp_redirect(remove_query_arg('mark_rented'));
                exit;
            }
        }
    }
}
add_action('template_redirect', 'rels_handle_property_actions');
