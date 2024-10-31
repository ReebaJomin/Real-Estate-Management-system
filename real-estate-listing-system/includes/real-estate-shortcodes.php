<?php
// Shortcode to display property listings
function rels_property_listings_shortcode($atts) {
    $args = array(
        'post_type' => 'property',
        'posts_per_page' => 10,
    );
    $query = new WP_Query($args);
    ob_start();
    if ($query->have_posts()) {
        echo '<ul class="property-listings">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li>';
            the_title('<h2>', '</h2>');
            echo '<p>' . get_post_meta(get_the_ID(), '_price', true) . '</p>';
            the_excerpt();
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No properties found</p>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
// Add this function in a relevant file, e.g., `real-estate-shortcodes.php`
function rels_property_search_form() {
    ob_start();
    ?>
    <form method="get" action="">
        <label for="location">Location:</label>
        <input type="text" name="location" id="location" value="<?php echo isset($_GET['location']) ? esc_attr($_GET['location']) : ''; ?>" /><br>

        <label for="price_min">Min Price:</label>
        <input type="number" name="price_min" id="price_min" value="<?php echo isset($_GET['price_min']) ? esc_attr($_GET['price_min']) : ''; ?>" /><br>

        <label for="price_max">Max Price:</label>
        <input type="number" name="price_max" id="price_max" value="<?php echo isset($_GET['price_max']) ? esc_attr($_GET['price_max']) : ''; ?>" /><br>

        <label for="bedrooms">Bedrooms:</label>
        <input type="number" name="bedrooms" id="bedrooms" value="<?php echo isset($_GET['bedrooms']) ? esc_attr($_GET['bedrooms']) : ''; ?>" /><br>

        <label for="property_type">Property Type:</label>
        <select name="property_type" id="property_type">
            <option value="">All Types</option>
            <option value="apartment" <?php selected('apartment', isset($_GET['property_type']) ? $_GET['property_type'] : ''); ?>>Apartment</option>
            <option value="house" <?php selected('house', isset($_GET['property_type']) ? $_GET['property_type'] : ''); ?>>House</option>
            <option value="commercial" <?php selected('commercial', isset($_GET['property_type']) ? $_GET['property_type'] : ''); ?>>Commercial</option>
        </select><br>

        <input type="submit" value="Search" />
    </form>
    <?php
    return ob_get_clean();
}

function rels_property_seeker_registration_form() {
    ob_start();
    ?>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required /><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required /><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required /><br>

        <input type="submit" name="submit" value="Register" />
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('property_seeker_registration', 'rels_property_seeker_registration_form');

function rels_property_owner_registration_form() {
    ob_start();
    ?>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="hidden" name="user_role" value="property_owner">
        <button type="submit">Register</button>
    </form>
    <?php
    return ob_get_clean();
}

function rels_property_search_results() {
    $args = array(
        'post_type' => 'property',
        'posts_per_page' => 10,
        'meta_query' => array(),
    );

    // Add filters based on user input
    if (!empty($_GET['location'])) {
        $args['meta_query'][] = array(
            'key' => 'location',
            'value' => sanitize_text_field($_GET['location']),
            'compare' => 'LIKE',
        );
    }

    if (!empty($_GET['price_min'])) {
        $args['meta_query'][] = array(
            'key' => 'price',
            'value' => intval($_GET['price_min']),
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
    }

    if (!empty($_GET['price_max'])) {
        $args['meta_query'][] = array(
            'key' => 'price',
            'value' => intval($_GET['price_max']),
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
    }

    if (!empty($_GET['property_type'])) {
        $args['meta_query'][] = array(
            'key' => 'property_type',
            'value' => sanitize_text_field($_GET['property_type']),
            'compare' => '=',
        );
    }

    if (!empty($_GET['bedrooms'])) {
        $args['meta_query'][] = array(
            'key' => 'bedrooms',
            'value' => intval($_GET['bedrooms']),
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        echo '<ul class="property-listings">';
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <li>
                <h2><?php the_title(); ?></h2>
                <p>Location: <?php echo get_post_meta(get_the_ID(), 'location', true); ?></p>
                <p>Price: <?php echo get_post_meta(get_the_ID(), 'price', true); ?></p>
                <p>Type: <?php echo get_post_meta(get_the_ID(), 'property_type', true); ?></p>
                <a href="<?php the_permalink(); ?>">View Details</a>
            </li>
            <?php
        }
        echo '</ul>';
    } else {
        echo '<p>No properties found</p>';
    }
    wp_reset_postdata();
}

add_shortcode('property_search_results', 'rels_property_search_results');


add_shortcode('property_owner_registration', 'rels_property_owner_registration_form');

add_shortcode('property_search_form', 'rels_property_search_form');

add_shortcode('property_listings', 'rels_property_listings_shortcode');
