<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Ensure Elementor is loaded before initializing the widget
function rels_register_elementor_widgets() {
    // Check if Elementor is active and loaded
    if ( did_action( 'elementor/loaded' ) ) {
        // Include your widget class
        class Property_Listings_Widget extends \Elementor\Widget_Base {

            public function get_name() {
                return 'property_listings';
            }

            public function get_title() {
                return __( 'Property Listings', 'text-domain' );
            }

            public function get_icon() {
                return 'eicon-post-list'; // Icon from Elementor
            }

            public function get_categories() {
                return [ 'general' ]; // The category your widget belongs to
            }

            protected function render() {
                // Output the shortcode for property listings
                echo do_shortcode('[property_listings]');
            }
        }

        // Register the widget with Elementor
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Property_Listings_Widget() );
    }
}

add_action( 'elementor/widgets/widgets_registered', 'rels_register_elementor_widgets' );
