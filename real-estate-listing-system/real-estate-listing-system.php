<?php
/*
Plugin Name: Real Estate Listing and Management System
Description: A custom real estate listing and management system for property owners, agents, and seekers.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('RELS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RELS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
include_once(RELS_PLUGIN_DIR . 'includes/real-estate-cpt.php');
include_once(RELS_PLUGIN_DIR . 'includes/real-estate-shortcodes.php');
include_once(RELS_PLUGIN_DIR . 'includes/real-estate-meta-boxes.php');
include_once(RELS_PLUGIN_DIR . 'includes/real-estate-elementor-widgets.php');
include_once(RELS_PLUGIN_DIR . 'includes/real-estate-dashboard.php');

function rels_create_custom_roles() {
    // Create Property Owner role
    add_role(
        'property_owner',
        'Property Owner',
        array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => false,
            'upload_files' => true,
        )
    );

    // Create Property Seeker role
    add_role(
        'property_seeker',
        'Property Seeker',
        array(
            'read' => true,
        )
    );
}

register_activation_hook(__FILE__, 'rels_create_custom_roles');

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'rels_activate');
register_deactivation_hook(__FILE__, 'rels_deactivate');

function rels_activate() {
    // Code to run on activation (e.g., create custom tables or flush rewrite rules)
}

function rels_deactivate() {
    // Code to run on deactivation (e.g., remove custom tables or reset settings)
}
