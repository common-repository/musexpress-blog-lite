<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.musegain.com
 * @since      1.0.0
 *
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/includes
 * @author     Eclipse srl <info@musegain.com>
 */
class Musexpress_Blog_Lite {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Musexpress_Blog_Lite_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'musexpress-blog-lite';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Musexpress_Blog_Lite_Loader. Orchestrates the hooks of the plugin.
	 * - Musexpress_Blog_Lite_i18n. Defines internationalization functionality.
	 * - Musexpress_Blog_Lite_Admin. Defines all hooks for the admin area.
	 * - Musexpress_Blog_Lite_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-musexpress-blog-lite-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-musexpress-blog-lite-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-musexpress-blog-lite-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-musexpress-blog-lite-public.php';

		$this->loader = new Musexpress_Blog_Lite_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Musexpress_Blog_Lite_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Musexpress_Blog_Lite_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Musexpress_Blog_Lite_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'musexpress_blog_lite_check_mxp_blog_version' );

		$this->loader->add_filter('musexpress_page_settings_option', $plugin_admin, 'musexpress_blog_lite_add_blog_page_settings');
		$this->loader->add_action('musexpress_page_settings_save', $plugin_admin, 'musexpress_blog_lite_page_settings');
		$this->loader->add_action('musexpress_after_page_conversion',$plugin_admin,'musexpress_blog_lite_lock_blog_category_pages',13);
		$this->loader->add_action( 'musexpress_template_creation_action', $plugin_admin, 'musexpress_blog_lite_blog_template_create', 10, 2);

		$this->loader->add_action( 'admin_notices', $plugin_admin,'musexpress_blog_lite_notice' );

		//Init della tab settings in General Musexpress
		$this->loader->add_filter('musexpress_general_settings_sections',$plugin_admin,'musexpress_blog_lite_settings_add_section',11,1);
		$this->loader->add_action('musexpress_general_settings_form_content', $plugin_admin, 'musexpress_blog_lite_settings_show_form', 11, 1);
		$this->loader->add_action('musexpress_general_settings_save', $plugin_admin, 'musexpress_blog_lite_settings_save', 11 ,1);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Musexpress_Blog_Lite_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'excerpt_length', $plugin_public, 'musexpress_blog_lite_custom_excerpt_length', 999 );
		$this->loader->add_filter( 'excerpt_more', $plugin_public, 'musexpress_blog_lite_custom_excerpt_more' );

		$this->loader->add_filter('template_include',$plugin_public,'musexpress_blog_lite_redirect_blog_pages',10);

		$this->loader->add_filter('get_the_archive_title', $plugin_public, 'musexpress_blog_lite_blog_archive_title',90);

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Musexpress_Blog_Lite_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
