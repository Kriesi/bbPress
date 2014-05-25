<?php
/*
Plugin Name: bbPress Private Field
Plugin URL: https://github.com/vatoyiit/bbPress
Description: Allows users to add private field to their replies which only the original poster and admins can see
Version: 1.0
Author: Yigit @ Kriesi.at
Author URI: http://kriesi.at
*/

	add_action( 'bbp_theme_after_topic_form_content', 'bbp_preply_field' );
	add_action( 'bbp_new_topic', 'bbp_save_preply_field', 10, 1 );
	add_action( 'bbp_edit_topic', 'bbp_save_preply_field', 10, 1 );
	add_action( 'bbp_theme_after_reply_content', 'bbp_show_preply_field' );


	/*
	 * Outputs the private field when creating a new topic
	 */
	function bbp_preply_field() {
		$reply_id = bbp_get_reply_id();
		$value = get_post_meta( $reply_id, 'bbp_preply_field', true);
		echo "<input name='bbp_preply_field' id='bbp_private_reply_text' type='text' placeholder='You can share private content here. Only you and moderators will see it.' value='".$value."'>";
	}
	
	/*
	* Saves the private field
	*/ 
	function bbp_save_preply_field($reply_id = 0) {
  		if (isset($_POST) && $_POST['bbp_preply_field']!='')
    		update_post_meta( $reply_id, 'bbp_preply_field', $_POST['bbp_preply_field'] );
	}

	/*
	* Displays the private field after topic created, to only author of the topic and to moderators
	*/
	function bbp_show_preply_field() {
  		$reply_id 	  = bbp_get_reply_id();
		$value 		  = get_post_meta( $reply_id, 'bbp_preply_field', true);
		$current_user = wp_get_current_user();
		$topic_author = bbp_get_topic_author_id();
		if( $topic_author == $current_user->ID or current_user_can( 'moderate' )) {
			echo "<div class='bbp_preply_field' style='color: #888;'>".$value."</div>";
		}
		else {
			echo "";
		}
	}










