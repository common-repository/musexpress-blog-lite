<?php


class Musexpress_blog_lite_error_handler{

   public static function musexpress_error($title,$message){

   	    $error_content = '<div class="MusexPress-Logo"><svg>
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="' . MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_URL . 'includes/assets/icons.svg#mg-logo"></use>
					</svg></div>';
   	    $error_content .= '<h2 class="MusexPress-Error-Title">' . $title . '</h2><div class="MusexPress-Error-Body">' . $message . '</div>';

   	    $error_content .='<div class="MusexPress-Actions">
			<ul><li>
				<a href="https://www.musegain.com/documentation/" target="_blank">
					<svg>
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="' . MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_URL . 'includes/assets/icons.svg#mg-documentation"></use>
					</svg>
					<span class="show-for-large">Documentation</span>
				</a>
			</li>
			<li>
				<a href="https://www.youtube.com/channel/UCozlJtbWQao1N_ViGp7BmuA" target="_blank">
					<svg>
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="' . MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_URL . 'includes/assets/icons.svg#mg-video-tutorials"></use>
					</svg>
					<span class="show-for-large">Tutorials</span>
				</a>
			</li>
			</ul></div>';

	   wp_die( $error_content, $title, array('back_link' => true) );

   }

    public static function musexpress_lite_error($title,$message){

        $error_content = '<div class="MusexPress-Logo"><svg>
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="' . MUSEXPRESS_BLOG_LITE_PLUGIN_DIRECTORY_URL . 'includes/assets/icons.svg#mg-logo"></use>
					</svg></div>';
        $error_content .= '<h2 class="MusexPress-Error-Title">' . $title . '</h2><div class="MusexPress-Error-Body">' . $message . '</div>';

        $error_content .='<div class="MusexPress-Actions">
			<ul><li>
				<a href="https://www.musegain.com/adobe-muse/musexpress/" target="_blank">
					<span class="show-for-large blog-lite-action-button">Download Now</span>
				</a>
			</li>
			</ul></div>';

        wp_die( $error_content, $title, array('back_link' => true) );

    }


   public static function musexpress_breakpoint_pretty_name($breakpoint){
	   $mess = substr($breakpoint, 3);

	   if($mess!=='infinity'){
	   	    return 'breakpoint ' . $mess;
	   }else{
	   	    return 'bigger breakpoint';
	   }

   }

}