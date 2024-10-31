<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.musegain.com
 * @since      1.0.0
 *
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/admin
 * @author     Eclipse srl <info@musegain.com>
 */
class Musexpress_Blog_Lite_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Musexpress_Blog_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Musexpress_Blog_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/musexpress-blog-lite-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-error-handler', plugin_dir_url( __FILE__ ) . 'css/error-handler.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Musexpress_Blog_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Musexpress_Blog_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/musexpress-blog-lite-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function musexpress_blog_lite_check_mxp_blog_version() {


		if ( empty( get_option( 'musexpress_blog_version' ) ) || get_option( 'musexpress_blog_version' ) != MUSEXPRESS_BLOG_LITE_PLUGIN_VERSION ) {
			require_once MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_PATH . 'includes/class-musexpress-blog-lite-activator.php';
			Musexpress_Blog_Lite_Activator::activate();
		}

	}



	public function musexpress_blog_lite_page_settings(){

		$page_settings = get_option( 'mxp_blog_page_settings' );



		foreach ( $page_settings as $settings_name => $settings ) {

		    // $_POST['mxp_page_settings'] comes from musexpress plugin settings and is a value of an HTML select. It should be a post slug.
			$current_setting = sanitize_title_with_dashes($_POST['mxp_page_settings'][ $settings_name ]);
            if(preg_match("/^[a-z0-9]+(?:-[a-z0-9]+)*$/", $current_setting)) {
	            $page_settings[ $settings_name ]['value'] = $current_setting;
            }

		}

		update_option( 'mxp_blog_page_settings', $page_settings );

		//Setta la pagina articoli
		$blog_page = get_page_by_path( get_option( 'mxp_blog_page_settings' )['archive_page']['value'] );
		if ( isset( $blog_page ) ) {
			update_option( 'page_for_posts', $blog_page->ID );

			if ( $blog_page->post_title == 'index' ) {
				update_option( 'show_on_front', 'posts' );
			} else {
				update_option( 'show_on_front', 'page' );
			}

		}

		update_option( 'mxp_blog_page_saved', true );


	}

	public function musexpress_blog_lite_add_blog_page_settings($options){

		return array_merge($options, get_option('mxp_blog_page_settings',true));

	}

	public function musexpress_blog_lite_blog_template_create( $page_parse, $additional_args) {

		require_once MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_PATH . 'admin/class-musexpress-blog-lite-page-converter.php';


		$page_name = $additional_args['page_name'];
		$base_theme_root_path = $additional_args['base_theme_root_path'];
		$user_theme_root_path = $additional_args['user_theme_root_path'];
		$matches = $additional_args['matches'];

		$page_converter = new Musexpress_Blog_Lite_Page_Converter( $page_parse, $base_theme_root_path, $user_theme_root_path, $page_name ,$matches );

		$page_converter->musexpress_blog_lite_create_template();

	}

	public function musexpress_blog_lite_lock_blog_category_pages(){

		$pages = get_pages();


		$pages_to_private = array();

		foreach ($pages as $page){

			if(strpos($page->post_title,'blog_')!==false){

				array_push($pages_to_private,$page);
			}
		}



		foreach ($pages_to_private as $page){

			wp_update_post(array('ID'    =>  $page->ID, 'post_status'   =>  'private'));

		}


	}

	public function musexpress_blog_lite_settings_add_section($sections){

		array_push($sections,'blog');

		return $sections;

	}

	public function musexpress_blog_lite_settings_show_form($selected_section){

		if($selected_section==='blog'){
			include plugin_dir_path(__FILE__).'/partials/musexpress-blog-lite-settings-loader.php';
		}

	}

	public function musexpress_blog_lite_settings_save($form_data){

		if(isset($form_data['selected_section'])){
			if($form_data['selected_section']==='blog'){
				$this->musexpress_blog_lite_post_excerpt_settings_save($form_data);
				$this->musexpress_blog_lite_archive_title_settings_save($form_data);
			}
		}


	}

	function musexpress_blog_lite_post_excerpt_settings_save($form_data){

		if(isset($form_data['mxp_posts_excerpt_lenght'])){
			update_option('mxp_posts_excerpt_lenght',$form_data['mxp_posts_excerpt_lenght']);
		}
		if(isset($form_data['mxp_posts_excerpt_more'])){
			update_option('mxp_posts_excerpt_more',$form_data['mxp_posts_excerpt_more']);
		}

	}

	function musexpress_blog_lite_archive_title_settings_save($form_data){

		if(isset($form_data['mxp_blog_archive_title'])) {
			update_option('mxp_blog_archive_title',$form_data['mxp_blog_archive_title']);
		}
		if(isset($form_data['mxp_blog_category_title'])) {
			update_option('mxp_blog_category_title',$form_data['mxp_blog_category_title']);
		}
		if(isset($form_data['mxp_blog_tag_title'])) {
			update_option('mxp_blog_tag_title',$form_data['mxp_blog_tag_title']);
		}
		if(isset($form_data['mxp_blog_author_title'])) {
			update_option('mxp_blog_author_title',$form_data['mxp_blog_author_title']);
		}

	}


	function musexpress_blog_lite_notice() {
		?>
		<div class="notice notice-info">
			<p>
			<div class="blog-lite-notice">You are using <strong>Lite</strong> version of the <strong>MusexPress Blog Plugin!</strong> Become a member to unleash all the potentiality of your Blog!
				<div style="text-align: right; float: right;">
					<a class="blog-lite-action-button" href="https://www.musegain.com/musexpress/">Discover Now</a>
					<a class="blog-lite-action-button" href="https://www.musegain.com/year-subscription/">Become a Member</a>
				</div>
			</div>
			</p>
		</div>
		<?php
	}

}
