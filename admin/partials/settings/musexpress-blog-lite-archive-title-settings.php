<h3><?php _e('Blog Title Options','blog-plugin'); ?></h3>
<p>
	<?php _e('<strong>Important:</strong> these settings are related only to the <strong>Blog Archive Title Widget</strong>.<br>Leave %s to get the name of category/tag/author, don\'t use it on Blog Page Title.','blog-plugin'); ?>
</p>
<table class="form-table">
	<tr>
		<td>
			<label for="mxp_blog_archive_title"><?php _e('Blog Page Title','blog-plugin'); ?></label>
		</td>
		<td>
			<input type="text" value="<?php echo get_option('mxp_blog_archive_title') ? get_option('mxp_blog_archive_title') : 'Archives'; ?>"
			       name="mxp_blog_archive_title"/>
		</td>
		<td>
			<?php _e('Title shown on post list page'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<label for="mxp_blog_category_title"><?php _e('Category Page Title','blog-plugin'); ?></label>
		</td>
		<td>
			<input type="text" value="<?php echo get_option('mxp_blog_category_title') ? get_option('mxp_blog_category_title') : 'Category: %s'; ?>"
			       name="mxp_blog_category_title"/>
		</td>
		<td>
			<?php _e('Title shown on blog category page'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<label for="mxp_blog_tag_title"><?php _e('Tag Page Title','blog-plugin'); ?></label>
		</td>
		<td>
			<input type="text" value="<?php echo get_option('mxp_blog_tag_title') ? get_option('mxp_blog_tag_title') : 'Tag: %s'; ?>"
			       name="mxp_blog_tag_title"/>
		</td>
		<td>
			<?php _e('Title shown on blog tag page'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<label for="mxp_blog_author_title"><?php _e('Author Page Title','blog-plugin'); ?></label>
		</td>
		<td>
			<input type="text" value="<?php echo get_option('mxp_blog_author_title') ? get_option('mxp_blog_author_title') : 'Author: %s'; ?>"
			       name="mxp_blog_author_title"/>
		</td>
		<td>
			<?php _e('Title shown on blog author page'); ?>
		</td>
	</tr>


</table>