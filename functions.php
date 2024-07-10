/**
 * Remove "uncategorized" category from all products.
 */
function remove_uncategorized_from_products() {
    // Get the "uncategorized" term
    $term = get_term_by('name', 'uncategorized', 'product_cat');

    if (is_wp_error($term) || !$term) {
        return; // Exit if the term is not found or there is an error
    }

    // Get all products with the "uncategorized" category
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $term->term_id,
            ),
        ),
    );
    $products = new WP_Query($args);

    // Loop through products and remove the "uncategorized" category
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();
            wp_remove_object_terms($product_id, $term->term_id, 'product_cat');
        }
    }

    wp_reset_postdata();
}
add_action('wp_loaded', 'remove_uncategorized_from_products');



