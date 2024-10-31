<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.musegain.com
 * @since      1.0.0
 *
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/includes
 * @author     Eclipse srl <info@musegain.com>
 */
class Musexpress_Blog_Lite_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'musexpress-blog-lite',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
