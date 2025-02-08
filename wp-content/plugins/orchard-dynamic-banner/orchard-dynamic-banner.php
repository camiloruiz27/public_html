<?php
/*
Plugin Name: Orchard Dynamic Banner
Description: A plugin to display a dynamic banner based on the menu structure.
Version: 1.0
Author: Your Name
*/

function orchard_dynamic_banner_enqueue_styles() {

}
add_action('wp_enqueue_scripts', 'orchard_dynamic_banner_enqueue_styles');

function orchard_get_top_level_menu_item( $menu_items, $current_item ) {

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


function orchard_get_banner_image_url() {
    $image_a_url = plugin_dir_url(__FILE__) . 'images/banner_a.jpg'; 
    $image_b_url = plugin_dir_url(__FILE__) . 'images/banner_b.jpg'; 

    $locations = get_nav_menu_locations();
    if (!isset($locations['Header'])) {
        error_log("No menu location 'Header' set.");
        return $image_b_url;
    }
    $menu = wp_get_nav_menu_object($locations['Header']);
    $menu_items = wp_get_nav_menu_items($menu->term_id);

    global $post;
    if (!$post) {
        error_log("No post global available.");
        return $image_b_url; 
    }
    $current_page_id = $post->ID;
    error_log("Current page ID: " . $current_page_id);

    $current_menu_item = null;
    foreach ($menu_items as $item) {
        if (intval($item->object_id) === intval($current_page_id)) {
            $current_menu_item = $item;
            error_log("Found menu item: " . $current_menu_item->title);
            break;
        }
    }
    
    if (!$current_menu_item) {
        error_log("No matching menu item found. Using default image.");
        return $image_b_url;
    }
    
    $top_item = orchard_get_top_level_menu_item($menu_items, $current_menu_item);
    error_log("Top-level menu item detected: " . $top_item->title);

    // Corrected conditions:
    if (strtolower($top_item->title) === 'root a') {
        return $image_a_url;
    } elseif (strtolower($top_item->title) === 'root b') {
        return $image_b_url;
    } else {
        return $image_a_url; 
    }
}



function orchard_dynamic_banner_shortcode() {
    $banner_image_url = orchard_get_banner_image_url();
    ob_start();
    ?>
    <div class="orchard-banner" style="height: 600px; background-image: url('<?php echo esc_url($banner_image_url); ?>');">
        <p style="color: red;">Debug Info: Banner URL: <?php echo esc_html($banner_image_url); ?></p>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('orchard_banner', 'orchard_dynamic_banner_shortcode');
