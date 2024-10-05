<?php

/**
 * Classic Editor
 *
 * Plugin Name: Book Plugin
 * Plugin URI:  #
 * Description: A custom post type for Books.
 * Version:     1.0.0
 * Author:      Amey
 * Author URI:  #
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: post-type-book
 * Domain Path: /languages
 * Requires at least: 4.0
 * Requires PHP: 5.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'PTB_VERSION', '1.0.0' );
define( 'PTB_PATH', plugin_dir_path( __FILE__ ) );
define( 'PTB_URL', plugin_dir_url( __FILE__ ) );

/**
 * Register activation hook
 */
register_activation_hook(__FILE__, 'book_post_type_activation');
function book_post_type_activation() {
    
    add_option('ptb_plugin_version', PTB_VERSION);
}

// require function file
require_once 'functions.php';