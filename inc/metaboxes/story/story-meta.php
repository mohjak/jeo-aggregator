<?php

add_action('add_meta_boxes', 'story_meta_add_meta_box');
add_action('save_post', 'story_meta_save_postdata');

function story_meta_add_meta_box() {
	add_meta_box(
		'story_meta',
		__('Story data', 'ekuatorial'),
		'story_meta_inner_custom_box',
		'post',
		'advanced',
		'high'
	);
}

function story_meta_inner_custom_box($post) {
	$is_external = get_post_meta($post->ID, 'is_external', true);
	$author_name = get_post_meta($post->ID, 'author_name', true);
	$author_email = get_post_meta($post->ID, 'author_email', true);
	$url = get_post_meta($post->ID, 'url', true);
	$pub_name = get_post_meta($post->ID, 'pub_name', true);
	$publish_date = get_post_meta($post->ID, 'publish_date', true);
	$notes = get_post_meta($post->ID, 'notes', true);
	?>
	<div id="story-metabox">
		<p>
			<label for="external_story"><?php _e('External Story', 'ekuatorial'); ?></label><br/>
			<input type="checkbox" name="is_external" value="1" <?php echo ($is_external == '1' ? 'checked' : '');?> id="external_story" size="30" />
		</p>
		<p>
			<label for="story_author_full_name"><?php _e('Author name', 'ekuatorial'); ?></label><br/>
			<input type="text" name="author_name" value="<?php echo $author_name; ?>" id="story_author_full_name" size="30" />
		</p>
		<p>
			<label for="story_author_email"><?php _e('Author email', 'ekuatorial'); ?></label><br/>
			<input type="text" name="author_email" value="<?php echo $author_email; ?>" id="story_author_email" size="35" />
		</p>
		<p>
			<label for="story_url"><?php _e('Story url', 'ekuatorial'); ?></label><br/>
			<input type="text" name="story_url" value="<?php echo $url; ?>" id="story_url" size="60" />
		</p>
		<p>
			<label for="pub_name"><?php _e('Publication Name', 'ekuatorial'); ?></label><br/>
			<input type="text" name="pub_name" value="<?php echo $pub_name; ?>" id="pub_name" size="60" />
		</p>
		<p>
			<label for="story_date"><?php _e('Publishing date', 'ekuatorial'); ?></label><br/>
			<input type="date" name="publish_date" value="<?php echo $publish_date; ?>" id="story_date" size="20" />
		</p>
		<p>
			<label for="story_notes"><?php _e('Notes to the ekuatorial editor', 'ekuatorial'); ?></label><br/>
			<textarea name="notes" id="story_notes" rows="7" cols="50"><?php echo $notes; ?></textarea>
		</p>
	</div>
	<?php
}

function story_meta_save_postdata($post_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	if (false !== wp_is_post_revision($post_id))
		return;

	if(isset($_POST['is_external'])) {
		update_post_meta($post_id, 'is_external', $_POST['is_external']);
	}
	else {
		update_post_meta($post_id, 'is_external', '');
	}
	if(isset($_POST['author_name']))
		update_post_meta($post_id, 'author_name', $_POST['author_name']);
	if(isset($_POST['author_email']))
		update_post_meta($post_id, 'author_email', $_POST['author_email']);
	if(isset($_POST['reporter']))
		update_post_meta($post_id, 'reporter', $_POST['reporter']);
	if(isset($_POST['story_url']))
		update_post_meta($post_id, 'url', $_POST['story_url']);
	if(isset($_POST['pub_name']))
		update_post_meta($post_id, 'pub_name', $_POST['pub_name']);
	if(isset($_POST['picture']))
		update_post_meta($post_id, 'picture', $_POST['picture']);
	if(isset($_POST['publish_date']))
		update_post_meta($post_id, 'publish_date', $_POST['publish_date']);
	if(isset($_POST['notes']))
		update_post_meta($post_id, 'notes', $_POST['notes']);

}

?>