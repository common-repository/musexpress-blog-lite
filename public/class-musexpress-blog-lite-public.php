<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.musegain.com
 * @since      1.0.0
 *
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Musexpress_Blog_Lite
 * @subpackage Musexpress_Blog_Lite/public
 * @author     Eclipse srl <info@musegain.com>
 */
class Musexpress_Blog_Lite_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/musexpress-blog-lite-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/musexpress-blog-lite-public.js', array( 'jquery' ), $this->version, false );

	}

	public function musexpress_blog_lite_custom_excerpt_length( $length ) {
		if ( get_option( 'mxp_excerpt_options' ) !== false ) {
			return get_option( 'mxp_excerpt_options' )['excerpt_length']['value'];
		} else {
			return get_option( 'mxp_posts_excerpt_lenght' );
		}
	}

	public function musexpress_blog_lite_custom_excerpt_more( $more ) {
		if ( get_option( 'mxp_excerpt_options' ) !== false ) {
			return get_option( 'mxp_excerpt_options' )['excerpt_more']['value'];
		} else {
			return get_option( 'mxp_posts_excerpt_more' );
		}
	}


	public function musexpress_blog_lite_redirect_blog_pages($template){

		if ( is_home() ) {
			return locate_template(  MUSEXPRESS_USER_THEME_NAME . '/' . get_option( 'mxp_blog_page_settings' )['archive_page']['value'] . '.php'  );
		} else if ( is_singular( 'post' ) ) {
			return locate_template(  MUSEXPRESS_USER_THEME_NAME . '/' . get_option( 'mxp_blog_page_settings' )['post_page']['value'] . '.php'  );
		} else if ( is_search() ) {
			return locate_template(  MUSEXPRESS_USER_THEME_NAME . '/' . get_option( 'mxp_blog_page_settings' )['archive_page']['value'] . '.php'  );
		} else if ( is_category() ) {
			$category = get_category( get_query_var( 'cat' ) );
			$cat_slug = $category->slug;

			if(!empty(locate_template(MUSEXPRESS_USER_THEME_NAME . '/blog_' . $cat_slug . '.php' ))){
				return locate_template(MUSEXPRESS_USER_THEME_NAME . '/blog_' . $cat_slug . '.php' );
			}
			else {
				return locate_template(  MUSEXPRESS_USER_THEME_NAME . '/' . get_option( 'mxp_blog_page_settings' )['archive_page']['value'] . '.php'  );
			}
		} else if(is_archive()){
			return locate_template(  MUSEXPRESS_USER_THEME_NAME . '/' . get_option( 'mxp_blog_page_settings' )['archive_page']['value'] . '.php'  );
		}

		return $template;


	}

	public function musexpress_blog_lite_blog_archive_title($title){

		if ( is_category() ) {
			$categories_title = get_option('mxp_blog_category_title') ? get_option('mxp_blog_category_title') : 'Category: %s';
			$title = sprintf( __( $categories_title ), single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$tag_title = get_option('mxp_blog_tag_title') ? get_option('mxp_blog_tag_title') : 'Tag: %s';
			$title = sprintf( __( $tag_title ), single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$author_title = get_option('mxp_blog_author_title') ? get_option('mxp_blog_author_title') : 'Author: %s';
			$title = sprintf( __( $author_title ), '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_year() ) {
			/* translators: Yearly archive title. 1: Year */
			$title = sprintf( __( 'Year: %s' ), get_the_date( _x( 'Y', 'yearly archives date format' ) ) );
		} elseif ( is_month() ) {
			/* translators: Monthly archive title. 1: Month name and year */
			$title = sprintf( __( 'Month: %s' ), get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );
		} elseif ( is_day() ) {
			/* translators: Daily archive title. 1: Date */
			$title = sprintf( __( 'Day: %s' ), get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title' );
			}
		} elseif ( is_post_type_archive() ) {
			/* translators: Post type archive title. 1: Post type name */
			$title = sprintf( __( 'Archives: %s' ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( __( '%1$s: %2$s' ), $tax->labels->singular_name, single_term_title( '', false ) );
		} else {
			$archive_title = get_option('mxp_blog_archive_title') ? get_option('mxp_blog_archive_title') : 'Archives';
			$title = __( $archive_title );
		}

		return $title;

	}

}
