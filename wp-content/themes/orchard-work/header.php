<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); // This hook is important for plugins and theme functions ?>
</head>
<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container">
            <!-- Insert Orchard Banner above the navigation -->
            <?php echo do_shortcode('[after_setup_theme]'); ?>
            
            <!-- Display site logo or site name -->
            <?php if ( function_exists('the_custom_logo') && has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <h1><?php bloginfo('name'); ?></h1>
            <?php endif; ?>
            
            <!-- Main Navigation Menu -->
            <nav class="main-navigation">
                <?php 
                // Display the menu assigned to the 'Header' location
                wp_nav_menu( array( 'theme_location' => 'Header' ) ); 
                ?>
            </nav>
        </div>
    </header>
