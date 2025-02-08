<?php
/*
Plugin Name: Product of the Day
Description: A custom plugin to manage and display a "Product of the Day". Admin can add multiple products and flag up to 5 products as candidates for the Product of the Day. A shortcode is provided to display a random featured product.
Version: 1.0
Author: Your Name
*/

register_activation_hook(__FILE__, 'pod_install');
function pod_install() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pod_products';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL statement to create the custom table.
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        summary text NOT NULL,
        image_url varchar(255) NOT NULL,
        is_featured tinyint(1) NOT NULL DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

//admin area menu

add_action('admin_menu', 'pod_admin_menu');
function pod_admin_menu() {
    add_menu_page(
        'Product of the Day',       // Page title
        'Product of the Day',       // Menu title
        'manage_options',           // Capability
        'pod_admin',                // Menu slug
        'pod_admin_page'            // Callback function to output the page content
    );
}

// admin area 

function pod_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pod_products';

    // ---------------------------------------------------
    // Process Deletion if URL contains action=delete and an id
    // ---------------------------------------------------
    if ( isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) ) {
        $id = intval($_GET['id']);
        $wpdb->delete($table_name, array('id' => $id));
        echo '<div class="updated"><p>Product deleted successfully.</p></div>';
    }
    
    // ---------------------------------------------------
    // Process Edit Submission if form is submitted with pod_action=edit
    // ---------------------------------------------------
    if ( isset($_POST['pod_action']) && $_POST['pod_action'] === 'edit' ) {
        $id = intval($_POST['id']);
        $name = sanitize_text_field($_POST['name']);
        $summary = sanitize_textarea_field($_POST['summary']);
        $image_url = esc_url_raw($_POST['image_url']);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        
        // If marking as featured, ensure maximum of 5 (excluding current product)
        if ( $is_featured ) {
            $featured_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_featured = 1 AND id != $id");
            if ( $featured_count >= 5 ) {
                echo '<div class="error"><p>Maximum of 5 featured products allowed.</p></div>';
            } else {
                $wpdb->update(
                    $table_name,
                    array(
                        'name'       => $name,
                        'summary'    => $summary,
                        'image_url'  => $image_url,
                        'is_featured'=> $is_featured
                    ),
                    array('id' => $id)
                );
                echo '<div class="updated"><p>Product updated successfully.</p></div>';
            }
        } else {
            $wpdb->update(
                $table_name,
                array(
                    'name'       => $name,
                    'summary'    => $summary,
                    'image_url'  => $image_url,
                    'is_featured'=> 0
                ),
                array('id' => $id)
            );
            echo '<div class="updated"><p>Product updated successfully.</p></div>';
        }
    }
    
    // ---------------------------------------------------
    // If editing, show the edit form pre-filled with product data.
    // ---------------------------------------------------
    if ( isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) ) {
        $id = intval($_GET['id']);
        $product = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
        if ( $product ) {
            ?>
            <div class="wrap">
                <h1>Edit Product</h1>
                <form method="post" action="">
                    <input type="hidden" name="pod_action" value="edit">
                    <input type="hidden" name="id" value="<?php echo esc_attr($product->id); ?>">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="name">Product Name</label></th>
                            <td><input name="name" type="text" id="name" class="regular-text" required value="<?php echo esc_attr($product->name); ?>"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="summary">Summary</label></th>
                            <td><textarea name="summary" id="summary" class="large-text" rows="5" required><?php echo esc_textarea($product->summary); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="image_url">Image URL</label></th>
                            <td><input name="image_url" type="text" id="image_url" class="regular-text" required value="<?php echo esc_url($product->image_url); ?>"></td>
                        </tr>
                        <tr>
                            <th scope="row">Featured Product?</th>
                            <td>
                                <input name="is_featured" type="checkbox" value="1" <?php checked($product->is_featured, 1); ?>> Check to mark as Product of the Day candidate
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('Update Product'); ?>
                </form>
            </div>
            <?php
            return;
        }
    }
    
    // ---------------------------------------------------
    // Process Add New Product Form Submission (only if not editing)
    // ---------------------------------------------------
    if ( ! ( isset($_GET['action']) && $_GET['action'] === 'edit' ) ) {
        if ( isset($_POST['pod_action']) && $_POST['pod_action'] === 'add' ) {
            $name = sanitize_text_field($_POST['name']);
            $summary = sanitize_textarea_field($_POST['summary']);
            $image_url = esc_url_raw($_POST['image_url']);
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
            if ( $is_featured ) {
                $featured_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_featured = 1");
                if ( $featured_count >= 5 ) {
                    echo '<div class="error"><p>Maximum of 5 featured products allowed.</p></div>';
                } else {
                    $wpdb->insert(
                        $table_name,
                        array(
                            'name'       => $name,
                            'summary'    => $summary,
                            'image_url'  => $image_url,
                            'is_featured'=> $is_featured,
                            'created_at' => current_time('mysql')
                        )
                    );
                    echo '<div class="updated"><p>Product added successfully.</p></div>';
                }
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'name'       => $name,
                        'summary'    => $summary,
                        'image_url'  => $image_url,
                        'is_featured'=> 0,
                        'created_at' => current_time('mysql')
                    )
                );
                echo '<div class="updated"><p>Product added successfully.</p></div>';
            }
        }
    }
    
    // ---------------------------------------------------
    // Retrieve and display all products for listing.
    // ---------------------------------------------------
    $products = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    ?>
    <div class="wrap">
        <h1>Product of the Day Management</h1>
        
        <?php if ( ! ( isset($_GET['action']) && $_GET['action'] === 'edit' ) ) : ?>
            <h2>Add New Product</h2>
            <form method="post" action="">
                <input type="hidden" name="pod_action" value="add">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="name">Product Name</label></th>
                        <td><input name="name" type="text" id="name" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="summary">Summary</label></th>
                        <td><textarea name="summary" id="summary" class="large-text" rows="5" required></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="image_url">Image URL</label></th>
                        <td><input name="image_url" type="text" id="image_url" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row">Featured Product?</th>
                        <td><input name="is_featured" type="checkbox" value="1"> Check to mark as Product of the Day candidate</td>
                    </tr>
                </table>
                <?php submit_button('Add Product'); ?>
            </form>
        <?php endif; ?>
        
        <h2>Existing Products</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Summary</th>
                    <th>Image URL</th>
                    <th>Featured</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products) : ?>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?php echo esc_html($product->id); ?></td>
                            <td><?php echo esc_html($product->name); ?></td>
                            <td><?php echo esc_html($product->summary); ?></td>
                            <td><?php echo esc_url($product->image_url); ?></td>
                            <td><?php echo $product->is_featured ? 'Yes' : 'No'; ?></td>
                            <td><?php echo esc_html($product->created_at); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=pod_admin&action=edit&id=' . $product->id); ?>">Edit</a> | 
                                <a href="<?php echo admin_url('admin.php?page=pod_admin&action=delete&id=' . $product->id); ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="7">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

//shortcode
function pod_product_of_the_day_shortcode() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pod_products';

    // Retrieve all products that are flagged as featured.
    $featured_products = $wpdb->get_results("SELECT * FROM $table_name WHERE is_featured = 1");

    // If no featured products are found, return a message.
    if ( ! $featured_products ) {
        return '<p>No featured products available.</p>';
    }

    // Select a random featured product.
    $random_product = $featured_products[array_rand($featured_products)];

    // Build the HTML output for the Product of the Day.
    ob_start();
    ?>
    <div class="product-of-the-day" style="border: 1px solid #ddd; padding: 20px;">
        <h2><?php echo esc_html($random_product->name); ?></h2>
        <p><?php echo esc_html($random_product->summary); ?></p>
        <div>
            <img src="<?php echo esc_url($random_product->image_url); ?>" alt="<?php echo esc_attr($random_product->name); ?>" style="max-width: 100%;">
        </div>
        <a href="#" class="cta-button" style="display: inline-block; margin-top: 10px; padding: 10px 20px; background: #0073aa; color: #fff; text-decoration: none;">View Product</a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('product_of_the_day', 'pod_product_of_the_day_shortcode');


function pod_all_products_shortcode() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pod_products';
    
    // Retrieve all products from the custom table.
    $products = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

    // If no products are found, return a message.
    if ( ! $products ) {
        return '<p>No products found.</p>';
    }

    // Build the HTML output for all products.
    ob_start();
    ?>
    <div class="all-products">
        <?php foreach ( $products as $product ) : ?>
            <div class="product-card" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                <h3><?php echo esc_html( $product->name ); ?></h3>
                <p><?php echo esc_html( $product->summary ); ?></p>
                <div>
                    <img src="<?php echo esc_url( $product->image_url ); ?>" alt="<?php echo esc_attr( $product->name ); ?>" style="max-width: 100%;">
                </div>
                <?php if ( $product->is_featured ) : ?>
                    <p style="color: green;">Featured</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('all_products', 'pod_all_products_shortcode');


