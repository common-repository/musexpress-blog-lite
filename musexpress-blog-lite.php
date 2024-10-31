<?php
/**
 *   Plugin Name: MusexPress Blog Lite
 *   Plugin URI:  http://www..musegain.com
 *   Description: This plugin enable MusexPress functionality and allows you to manage your Adobe Muse website contents.
 *   Version:     1.0.0
 *   Author:      MuseGain.com
 *   Author URI:  http://www.musegain.com
 *   License:     MuseGain.com
 *   License URI: http://www.musegain.com/terms-conditions-of-use/#musexpress-blog-lite
 *   Text Domain: musexpress-blog-lite
 *   Domain Path: /languages
 *
 *   All the products on Musegain.com are copyrighted and are the properties of
 *   Eclipse s.r.l.
 *   You acknowledge that by Your download the ownership of MusexPress Blog Lite
 *   (and/or its plugins) does not get transferred to You and You must not claim
 *   that it is Yours.
 *   What You get includes an ongoing, non-exclusive, worldwide
 *   license to use the plugin on the terms and conditions available here
 *   http://www.musegain.com/terms-conditions-of-use/#musexpress-blog-lite . Please read them carefully,
 *   any violation will not be tolerated.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MUSEXPRESS_BLOG_LITE_PLUGIN_NAME', 'musexpress-blog-lite' );
define( 'MUSEXPRESS_BLOG_LITE_PLUGIN_VERSION', '1.0.0' );
define( 'MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_PATH', plugin_dir_path( __FILE__ ) );
define( 'MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_URL', plugin_dir_url( __FILE__ ) );
//DON'T FORGET TO CHANGE FOR EVERY TAG!
define( 'MUSEXPRESS_BLOG_LITE_UPDATE_DB' , true);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-musexpress-blog-lite-activator.php
 */
function activate_musexpress_blog_lite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-musexpress-blog-lite-activator.php';
	Musexpress_Blog_Lite_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-musexpress-blog-lite-deactivator.php
 */
function deactivate_musexpress_blog_lite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-musexpress-blog-lite-deactivator.php';
	Musexpress_Blog_Lite_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_musexpress_blog_lite' );
register_deactivation_hook( __FILE__, 'deactivate_musexpress_blog_lite' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-musexpress-blog-lite.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_musexpress_blog_lite() {

	$plugin = new Musexpress_Blog_Lite();
	$plugin->run();

}

run_musexpress_blog_lite();
