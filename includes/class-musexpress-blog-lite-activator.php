<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.musegain.com
 * @since      1.0.0
 *
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/includes
 * @author     Eclipse srl <info@musegain.com>
 */
class Musexpress_Blog_Lite_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once plugin_dir_path( __FILE__ ) .'class-musexpress-blog-lite-error-handler.php';

		if(!is_plugin_active('musexpress/musexpress.php')){
			Musexpress_blog_lite_error_handler::musexpress_lite_error('MusexPress Not Active', 'To use this plugin you need to install and activate MusexPress CMS. It seems it is not active on your WordPress Admin Panel. You can download MusexPress freely from our Website!');
		}


		add_option( 'mxp_blog_page_saved' , false );

		if(MUSEXPRESS_BLOG_LITE_UPDATE_DB){
			update_option('mxp_blog_page_settings', array(

				'archive_page' => array(
					'label' => 'Posts List Page',
					'value' => 'post_list'
				),
				'post_page'    => array(
					'label' => 'Single Post Page',
					'value' => 'post'
				),

			));
		}else{
			add_option('mxp_blog_page_settings', array(

				'archive_page' => array(
					'label' => 'Posts List Page',
					'value' => 'post_list'
				),
				'post_page'    => array(
					'label' => 'Single Post Page',
					'value' => 'post'
				),

			));
		}

		add_option( 'mxp_posts_excerpt_lenght', 55 );
		add_option( 'mxp_posts_excerpt_more', '[...]' );


		update_option( 'musexpress_blog_version', MUSEXPRESS_BLOG_LITE_PLUGIN_VERSION );
	}

}
