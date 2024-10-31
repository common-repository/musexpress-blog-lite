<?php

class Musexpress_Blog_Lite_Page_Converter {
	private $page_parse;
	private $base_theme_root_path;
	private $user_theme_root_path;
	private $page_name;
	private $matches;


	public function __construct( $page_parse, $base_theme_root_path, $user_theme_root_path, $page_name,$matches) {

		require_once MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_PATH . 'includes/class-musexpress-blog-lite-utility.php';
		require_once MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_PATH . 'includes/class-musexpress-blog-lite-error-handler.php';

		$this->page_parse = $page_parse;
		$this->base_theme_root_path = $base_theme_root_path;
		$this->user_theme_root_path = $user_theme_root_path;
		
		$this->page_name = $page_name;
		$this->matches = $matches;


	}
	
	public function musexpress_blog_lite_create_template(){
		//Update posts_per_page
		if ( $this->page_parse->find( '.mxp_posts_per_page ', 0 ) ) {

			$attrs = $this->page_parse->find( '.mxp_posts_per_page ', 0 )->attr;
			foreach ( $attrs as $key => $value ) {

				if ( $key == 'data-mxp' && $value == 'true' ) {
					$posts_per_page = intval( $this->page_parse->find( '.mxp_posts_per_page', 0 )->innertext );
					update_option( 'posts_per_page', $posts_per_page );
				}
			}

		}


		//Crea una pagina template per ogni post grid
		$id = array();

		foreach ( $this->page_parse->find( '.musegain_container' ) as $container ) {

			array_push( $id, $container->id );

		}

		foreach ($this->page_parse->find('.MusexPress-Posts-Template') as $elem ){

			$the_post = $this->musexpress_blog_lite_find_id_posts_template($elem);
			if($the_post!==''){
				$this->musexpress_blog_lite_convert_posts_template($elem,$the_post);
			}else{

				$this->musexpress_blog_lite_remove_classes($elem);
			}

		}



		foreach ( $this->page_parse->find( '.MusexPress-Blog-Next-Button' ) as $elem ) {


			$elem->outertext = '<?php if(get_next_posts_link()!=null):?><?php echo str_replace("</a>","",get_next_posts_link( " " )); ?>' . $elem . '</a><?php endif; ?>';

		}

		foreach ( $this->page_parse->find( '.MusexPress-Blog-Previous-Button' ) as $elem ) {


			$elem->outertext = '<?php if(get_previous_posts_link()!=null):?><?php echo str_replace("</a>","",get_previous_posts_link( " " )); ?>' . $elem . '</a><?php endif; ?>';

		}


		foreach ( $this->page_parse->find( '.MusexPress-Blog-Numbers' ) as $elem ) {


			$elem->last_child()->innertext = '  <?php echo paginate_links( array(
\'mid_size\'           => 1,
\'prev_next\'          => true,
\'prev_text\'          => " ",
\'next_text\'          => " "

)); ?>';
		}

		foreach ( $this->page_parse->find( '.MusexPress-Post-Title' ) as $elem ) {


			$last_child = $elem->last_child();
			if(!empty($last_child->last_child())){

				$last_child->last_child()->innertext = '<?php echo get_the_title(); ?>';
			}
			else{
				$last_child->innertext = '<?php echo get_the_title(); ?>';
			}

		}


		foreach ( $this->page_parse->find( '.MusexPress-Post-Date' ) as $post_date ) {


			$post_date->last_child()->innertext = '<?php echo get_the_date( "", \'\', \'\', true ); ?>';
		}

		foreach ( $this->page_parse->find( '.MusexPress-Post-Comments' ) as $comments_number ) {


			$comments_number->last_child()->innertext = '<?php if(comments_open()) {
          echo get_comments_number();
           }
           else
             echo __("Comments are closed.");
           ?>';
		}

		foreach ( $this->page_parse->find( '.MusexPress-Post-Category-List' ) as $category_link ) {


			$sep_matches = array();
			preg_match( '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $category_link->last_child()->innertext, $sep_matches );
			$separator = ' ';
			if ( isset( $sep_matches[0] ) ) {
				$separator = $sep_matches[0];
			}
			$category_link->innertext = '<?php echo get_the_category_list(" ' . $separator . ' "); ?>';

		}

		foreach ( $this->page_parse->find( '.MusexPress-Post-Tag-List' ) as $tag_link ) {


			$sep_matches = array();
			preg_match( '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $tag_link->last_child()->innertext, $sep_matches );
			$separator = ' ';
			if ( isset( $sep_matches[0] ) ) {
				$separator = $sep_matches[0];
			}
			$tag_link->innertext = '<?php echo get_the_tag_list(""," ' . $separator . ' "); ?>';

		}
		foreach ( $this->page_parse->find( '.MusexPress-Post-Image' ) as $image ) {

			$image->attr['class'] = str_replace( 'MusexPress-Post-Image', '', $image->attr['class'] );
            $image->attr['style'] = '<?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); echo "background-image: url(".$url.");";?>';

		}

		foreach ( $this->page_parse->find( '.MusexPress-Post-Prev-Link' ) as $prev_link ){

			$prev_href = '<?php $prev = get_permalink(get_adjacent_post(false,"",false)); if ($prev != get_permalink()) { ?><a href="<?php echo $prev; ?>">' . $prev_link . '</a><?php } ?>';

			$prev_link->outertext = $prev_href;
		}

		foreach ( $this->page_parse->find( '.MusexPress-Post-Next-Link' ) as $next_link ){

			$next_href = '<?php $next = get_permalink(get_adjacent_post(false,"",true)); if ($next != get_permalink()) { ?><a href="<?php echo $next; ?>">' . $next_link . '</a><?php } ?>';

			$next_link->outertext = $next_href;
		}


		foreach ( $this->page_parse->find( '.MusexPress-Blog-NoContent' ) as $no_content ){

			$no_content->outertext = '<?php if(!have_posts()) : ?>'.$no_content.'<?php endif; ?>';
		}

	}
	

	function musexpress_blog_lite_convert_posts_template($elem, $the_post){


		foreach ( $elem->find( '.MusexPress-Posts-Title' ) as $post_title ) {


			if ( ! empty( $post_title ) ) {

				$last_child = $post_title->last_child();
				if(!empty($last_child->last_child())){

					$last_child->last_child()->innertext = '<?php echo get_the_title(); ?>';
				}
				else{
					$last_child->innertext = '<?php echo get_the_title(); ?>';
				}

				$post_title->attr['href'] = '<?php echo get_the_permalink(); ?>';
				$post_title->attr['class'] = str_replace( 'MusexPress-Posts-Title', '', $post_title->attr['class'] );

			}

		}

		foreach ( $elem->find('.MusexPress-Posts-Image') as $image ) {
			if ( ! empty( $image ) ) {

				$style = '<?php $url = wp_get_attachment_url( get_post_thumbnail_id('.$the_post.'->ID) ); echo "background-image: url(".$url.");";?>';
				$image->attr['style'] = $style;
				$orig_id = $image->attr['id'];
				$the_link = '<?php echo get_the_permalink('.$the_post.'); ?>';
				$image->attr['mxp-link'] = $the_link;

				foreach ( $this->page_parse->find('[data-orig-id="'.$orig_id.'"]') as $other_img ){
					$other_img->attr['style'] = $style;
					$other_img->attr['mxp-link'] = $the_link;
				}
				$image->attr['class'] = str_replace( 'MusexPress-Posts-Image', '', 	$image->attr['class'] );
			}
		}


		foreach ( $elem->find( '.MusexPress-Posts-Excerpt' ) as $post_excerpt ) {


			if ( ! empty( $post_excerpt ) ) {

				$post_excerpt->innertext = '<?php echo get_the_excerpt(); ?>';
				$post_excerpt->attr['class'] = str_replace( 'MusexPress-Posts-Excerpt', '', $post_excerpt->attr['class'] );

			}
		}


		foreach ( $elem->find( '.MusexPress-Posts-Link' ) as $post_link ) {


			if ( ! empty( $post_link ) ) {

				$post_link->attr['href'] = '<?php echo get_the_permalink(); ?>';
				$post_link->attr['class'] = str_replace( 'MusexPress-Posts-Link', '', $post_link->attr['class'] );
				$orig_id = $post_link->attr['id'];
				$the_link = '<?php echo get_the_permalink('.$the_post.'); ?>';

				foreach ( $this->page_parse->find('[data-orig-id="'.$orig_id.'"]') as $other_links ){
					$other_links->attr['href'] = $the_link;
				}

			}

		}


		foreach ( $elem->find( '.MusexPress-Posts-Date' ) as $post_date ) {


			if ( ! empty( $post_date ) ) {

				$post_date->innertext = '<?php echo get_the_date( get_option(\'date_format\')); ?>';
				$post_date->attr['class'] = str_replace( 'MusexPress-Posts-Date', '', $post_date->attr['class'] );

			}

		}
		foreach ( $elem->find( '.MusexPress-Posts-Comments' ) as $comments_number ) {


			if ( ! empty( $comments_number ) ) {

				$comments_number->last_child()->innertext = '<?php echo get_comments_number(); ?>';
				$comments_number->attr['class'] = str_replace( 'MusexPress-Posts-Comments', '', $comments_number->attr['class'] );

			}
		}


		foreach ( $elem->find( '.MusexPress-Posts-Author-Name' ) as $author_name ) {

			if ( ! empty( $author_name ) ) {

				$author_name->attr['href'] = '<?php $post_author_id = get_post_field( \'post_author\' ); echo get_author_posts_url( $post_author_id );?>';

				$author_name->innertext = '<?php echo get_the_author_meta( "nicename", $post_author_id  ); ?>';

				$author_name->attr['class'] = str_replace( 'MusexPress-Posts-Author-Name', '', $author_name->attr['class'] );

			}

		}

		foreach ( $elem->find( '.MusexPress-Posts-Author-Image' ) as $author_image ) {


			if ( ! empty( $author_image ) ) {

				$style = '<?php $post_author_id = get_post_field( \'post_author\', '.$the_post.'->ID ); $author_url = get_avatar_url( $post_author_id ); echo "background-image: url(".$author_url.");" ;?>';
				$the_link = '<?php $post_author_id = get_post_field( \'post_author\', '.$the_post.'->ID ); echo get_author_posts_url( $post_author_id ); ?>';

				$author_image->attr['style'] = $style;
				$author_image->attr['href'] = $the_link;
				$orig_id = $author_image->attr['id'];
				$author_image->attr['class'] = str_replace( 'MusexPress-Posts-Author-Image', '', $author_image->attr['class'] );

				foreach ( $this->page_parse->find('[data-orig-id="'.$orig_id.'"]') as $other_img ){
					$other_img->attr['style'] = $style;
					$other_img->attr['href'] = $the_link;
				}
			}
		}


		foreach ( $elem->find( '.MusexPress-Posts-Category-List' ) as $category_link ) {


			$sep_matches = array();
			preg_match( '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $category_link->last_child()->innertext, $sep_matches );
			$separator = ' ';
			if ( isset( $sep_matches[0] ) ) {
				$separator = $sep_matches[0];
			}
			$category_link->innertext = '<?php echo get_the_category_list(" ' . $separator . ' "); ?>';
			$category_link->attr['class'] = str_replace( 'MusexPress-Posts-Category-List', '', $category_link->attr['class'] );

		}

		foreach ( $elem->find( '.MusexPress-Posts-Tag-List' ) as $tag_link ) {

			$sep_matches = array();
			preg_match( '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $tag_link->last_child()->innertext, $sep_matches );
			$separator = ' ';
			if ( isset( $sep_matches[0] ) ) {
				$separator = $sep_matches[0];
			}
			$tag_link->innertext = '<?php echo get_the_tag_list(""," ' . $separator . ' "); ?>';
			$tag_link->attr['class'] = str_replace( 'MusexPress-Posts-Tag-List', '', $tag_link->attr['class'] );

		}


		$start_check_script = '<?php if(isset('.$the_post.')) : ?><?php global $post; $post = '.$the_post.'; setup_postdata('.$the_post.'); ?>';
		$end_script = '<?php wp_reset_postdata(); ?><?php endif; ?>';
		$elem->innertext = $start_check_script . $elem->innertext . $end_script;

		$orig_container_id = $elem->attr['id'];

		foreach ( $this->page_parse->find('[data-orig-id="'.$orig_container_id.'"]') as $other_containers ){
			$other_containers->innertext = $start_check_script . $other_containers->innertext . $end_script;
			$other_containers->attr['class'] = str_replace( 'MusexPress-Posts-Template', 'MusexPress-Posts-Template-Container', $other_containers->attr['class'] );

		}

		$elem->attr['style'] = $elem->attr['style'] .' <?php echo isset('.$the_post.') ? "" : "; display: none;" ;?> ';
		$elem->attr['class'] = str_replace( 'MusexPress-Posts-Template', 'MusexPress-Posts-Template-Container', $elem->attr['class'] );

	}

	function musexpress_blog_lite_find_id_posts_template($elem){
		$id = Musexpress_blog_lite_utility::get_string_between( $elem->find( '.MusexPress-Posts-Title ', 0 )->innertext, '[', ']' );

		if(!empty($id)){
			return '$posts_'.str_replace('_','[',$id).'-1]';
		}
		return '';
	}

	function musexpress_blog_lite_remove_classes($elem){

		$innertext = $elem->innertext;

		$classes_to_remove = array ('MusexPress-Posts-Title','MusexPress-Posts-Image','MusexPress-Posts-Excerpt',
			'MusexPress-Posts-Link','MusexPress-Posts-Main-Category','MusexPress-Posts-Tag-List','MusexPress-Posts-Date',
			'MusexPress-Posts-Comments',
			'MusexPress-Posts-Author-Name','MusexPress-Posts-Author-Image','MusexPress-Posts-Category-List');

		foreach ( $classes_to_remove as $class ){
			$innertext = str_replace($class,'',$innertext);
		}

		$innertext = str_replace( 'MusexPress-Posts-Template', 'MusexPress-Posts-Template-Container', $innertext );

        $elem->innertext = $innertext;


	}


}