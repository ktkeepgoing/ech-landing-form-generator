<?php
/**
 * Primecare Doctors Slick Slider Plugin
 *
 * @link              https://primecare.com.hk/
 * @since             1.0.0
 * @package           ECH_Landing_Form_Generator
 * @wordpress-plugin
 * Plugin Name:       ECH Landing Form Generator
 * Plugin URI:        https://iechealthcare-edu.com 
 * 
 * Description:       This plugin creates a landing form shortcode. 
 * 
 *                    
 * Version:           1.0.0
 * Author:            Toby Wong
 * Author URI:        https://primecare.com.hk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ech-post-list-cards
 * Domain Path:       /languages
 */


if (! defined('ABSPATH')) {
	exit;
}


// loader.php is to load all files in folder "inc"
require_once(dirname(__FILE__). '/inc/loader.php');


// include CSS and JS files
add_action('init', 'register_ech_lfg_styles');
add_action('wp_enqueue_scripts', 'enqueue_ech_lfg_styles');


//load more posts using ajax
/*
add_action('wp_ajax_nopriv_pl_ajax_load_posts', 'pl_ajax_load_posts');
add_action('wp_ajax_pl_ajax_load_posts', 'pl_ajax_load_posts');
*/

// Register the shortcode
add_shortcode('ech_lfg', 'ech_lfg_fun' );


