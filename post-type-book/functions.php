<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Book Post Type
 */
function ptb_create_book_post_type() {
    $labels = array(
        'name'                  => _x('Books', 'Post Type General Name', 'post-type-book'),
        'singular_name'         => _x('Book', 'Post Type Singular Name', 'post-type-book'),
        'menu_name'             => __('Books', 'post-type-book'),
        'name_admin_bar'        => __('Book', 'post-type-book'),
        'archives'              => __('Book Archives', 'post-type-book'),
        'attributes'            => __('Book Attributes', 'post-type-book'),
        'parent_item_colon'     => __('Parent Book:', 'post-type-book'),
        'all_items'             => __('All Books', 'post-type-book'),
        'add_new_item'          => __('Add New Book', 'post-type-book'),
        'add_new'               => __('Add New', 'post-type-book'),
        'new_item'              => __('New Book', 'post-type-book'),
        'edit_item'             => __('Edit Book', 'post-type-book'),
        'update_item'           => __('Update Book', 'post-type-book'),
        'view_item'             => __('View Book', 'post-type-book'),
        'view_items'            => __('View Books', 'post-type-book'),
        'search_items'          => __('Search Book', 'post-type-book'),
    );
    $args = array(
        'label'                 => __('Book', 'post-type-book'),
        'description'           => __('Custom post type for Books', 'post-type-book'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies'            => array('genre'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 10,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type('book', $args);
}
add_action('init', 'ptb_create_book_post_type');


/**
 * add meta box for book post type
 */
function ptb_book_meta_boxes() {
    add_meta_box('book_details', 'Book Details', 'ptb_book_meta_box_callback', 'book', 'normal', 'high');
}
add_action('add_meta_boxes', 'ptb_book_meta_boxes');

/**
 * meta box callback function
 */
function ptb_book_meta_box_callback($post) {
    $author = get_post_meta($post->ID, 'book_author', true);
    $publisher = get_post_meta($post->ID, 'book_publisher', true);
    ?>

    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="book_author"><?php esc_html_e('Author', 'post-type-book');?>:</label>
                </th>
                <td>
                    <input type="text" class="regular-text" name="book_author" id="book_author" value="<?php echo esc_attr($author); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="book_publisher"><?php esc_html_e('Publisher', 'post-type-book');?>:</label>
                </th>
                <td>
                    <input type="text" class="regular-text" name="book_publisher" id="book_publisher" value="<?php echo esc_attr($publisher); ?>" />
                </td>
            </tr>
        </tbody>
    </table>

    <?php
}

/**
 * save meta box fields
 */
function ptb_save_book_meta($post_id) {
    if (array_key_exists('book_author', $_POST)) {
        update_post_meta($post_id, 'book_author', sanitize_text_field($_POST['book_author']));
    }
    if (array_key_exists('book_publisher', $_POST)) {
        update_post_meta($post_id, 'book_publisher', sanitize_text_field($_POST['book_publisher']));
    }
}
add_action('save_post', 'ptb_save_book_meta');

/**
 * Shortcode to display books: [display_books]
 */
function ptb_display_books_function() {
    $args = array(
        'post_type' => 'book',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    $output = '<ul>';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $author = get_post_meta(get_the_ID(), 'book_author', true);
            $publisher = get_post_meta(get_the_ID(), 'book_publisher', true);
            $output .= '<li>';
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<p>' . get_the_content() . '</p>';
            $output .= '<p><strong>'. esc_html__('Author', 'post-type-book') .':</strong> ' . esc_html($author) . '</p>';
            $output .= '<p><strong>'. esc_html__('Publisher', 'post-type-book') .':</strong> ' . esc_html($publisher) . '</p>';
            $output .= '</li>';
        }
        wp_reset_postdata();
    } else {
        $output .= '<li>No books found.</li>';
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode('display_books', 'ptb_display_books_function');