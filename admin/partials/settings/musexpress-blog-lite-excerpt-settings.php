<h3><?php _e('Blog Excerpt Options','musexpress'); ?></h3>

<table class="form-table">
	<tr>
		<td>
			<label for="mxp_posts_excerpt_lenght"><?php _e('Excerpt Length','blog-plugin'); ?></label>
		</td>
		<td>
			<input type="number" value="<?php echo get_option( 'mxp_posts_excerpt_lenght' ); ?>"
			       name="mxp_posts_excerpt_lenght"/>
		</td>
		<td>
			<?php _e('Number of words of the post excerpt','musexpress'); ?>
		</td>
	</tr>

	<tr>
		<td>
			<label for=mxp_posts_excerpt_more"><?php _e('Excerpt More Text','blog-plugin'); ?></label>
		</td>
		<td>
			<input type="text" value="<?php echo get_option( 'mxp_posts_excerpt_more' ); ?>"
			       name="mxp_posts_excerpt_more"/>
		</td>
		<td>
			<?php _e('Last word of the post excerpt','blog-plugin'); ?>
		</td>
	</tr>

</table>