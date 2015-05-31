<?php
/*
Plugin Name: Vp Autocomplete Widget
Plugin URI: http://alkoweb.ru/
Version: 1.0
Author: petrozavodsky
Author URI: http://alkoweb.ru/
License: GPL2
*/


/**
 * Enqueue admin testimonials javascript
 */
function testimonials_enqueue_scripts() {
	wp_enqueue_script(
		'admin-testimonials', plugin_dir_url(__FILE__) . '/js/admin-testimonials.js',
		array( 'jquery', 'underscore', 'backbone' )
	);
}
add_action( 'admin_enqueue_scripts', 'testimonials_enqueue_scripts' );


require plugin_dir_path(__FILE__) . '/access/class-testimonial-widget.php';
