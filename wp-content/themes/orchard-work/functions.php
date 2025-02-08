<?php
// Enqueue child theme styles if needed
function orchard_theme_enqueue_styles() {
    wp_enqueue_style('child-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'orchard_theme_enqueue_styles');

function orchard_get_top_level_menu_item( $menu_items, $current_item ) {
    // Loop until the parent is 0 (indicating a top-level item)
    while ( $current_item->menu_item_parent != 0 ) {
        foreach ( $menu_items as $item ) {
            if ( $item->ID == $current_item->menu_item_parent ) {
                $current_item = $item;
                break;
            }
        }
    }
    return $current_item;
}


function orchard_get_banner_image_urls() {

    $image_a_url = get_stylesheet_directory_uri() . '/images/banner_a.jpg'; // Image for Root A
    $image_b_url = get_stylesheet_directory_uri() . '/images/banner_b.jpg'; // Image for Root B

    $locations = get_nav_menu_locations();
    if ( ! isset( $locations['Header'] ) ) {
        error_log("No menu location 'Header' set.");
        return array($image_a_url); // Default image as an array
    }
    $menu = wp_get_nav_menu_object( $locations['Header'] );
    $menu_items = wp_get_nav_menu_items( $menu->term_id );

    global $post;
    if ( ! $post ) {
        error_log("No post global available.");
        return array($image_a_url);
    }
    $current_page_id = $post->ID;
    error_log("Current page ID: " . $current_page_id);

    $matching_items = array();
    foreach ( $menu_items as $item ) {
        if ( intval($item->object_id) === intval($current_page_id) ) {
            $matching_items[] = $item;
            error_log("Found menu item: " . $item->title);
        }
    }
    
    if ( empty($matching_items) ) {
        error_log("No matching menu item found. Using default image.");
        return array($image_a_url);
    }
    
    $banner_urls = array();
    foreach ( $matching_items as $current_menu_item ) {
        // Get the top-level menu item for this matching item.
        $top_item = orchard_get_top_level_menu_item( $menu_items, $current_menu_item );
        error_log("Top-level menu item detected: " . $top_item->title);

        if ( strtolower( trim($top_item->title) ) === 'root a' ) {
            $banner_urls[] = $image_a_url;
        } elseif ( strtolower( trim($top_item->title) ) === 'root b' ) {
            $banner_urls[] = $image_b_url;
        } else {
            $banner_urls[] = $image_a_url;
        }
    }
    return $banner_urls;
}

function orchard_dynamic_banner_shortcode() {
    $banner_image_urls = orchard_get_banner_image_urls();
    ob_start();
    ?>
    <div class="orchard-banners">
        <?php foreach ( $banner_image_urls as $banner_image_url ) : ?>
            <div class="orchard-banner" style="height: 600px; background-image: url('<?php echo esc_url( $banner_image_url ); ?>');">
                <!-- Debug: Show banner image URL -->
                <p style="color: red;">Debug Info: Banner URL: <?php echo esc_html( $banner_image_url ); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'orchard_banner', 'orchard_dynamic_banner_shortcode' );


function orchard_work_register_menus() {
    register_nav_menus( array(
        'Header' => __( 'Header Menu', 'orchard-work' ) // Register a menu location with the identifier 'Header'
    ));
}
add_action( 'after_setup_theme', 'orchard_work_register_menus' );
