<?php
/*
Plugin Name: Avia Private Data
Plugin URL: https://github.com/vatoyiit/bbPress
Description: Allows users to add private field to their replies
Version: 1.0
Author: Yigit @ Kriesi.at
Author URI: http://kriesi.at
*/

	add_action( 'bbp_theme_after_topic_form_content', 'bbp_preply_field' );
	add_action( 'bbp_new_topic', 'bbp_save_preply_field', 10, 1 );
	add_action( 'bbp_edit_topic', 'bbp_save_preply_field', 10, 1 );
	add_action( 'bbp_theme_after_reply_content', 'bbp_show_preply_field', 1 );


	/*
	 * Outputs the private field when creating a new topic
	 */
	function bbp_preply_field() {
		$reply_id = bbp_get_topic_id();
		$value = get_post_meta( $reply_id, 'bbp_preply_field', true);
		
		$output = "";
		$output .= "<p>";
		$output .= "<label>You can share private content like a link to your website or login credentials here. Only you and moderators will see it.</label>";
		$output .= "<textarea name='bbp_preply_field' id='bbp_private_reply_text'>{$value}</textarea>";
		$output .= "</p>";
		
		echo $output;
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
	function bbp_show_preply_field() 
	{
  		$reply_id 	  = bbp_get_topic_id();
		$value 		  = get_post_meta( $reply_id, 'bbp_preply_field', true);
		$current_user = wp_get_current_user();
		$topic_author = bbp_get_topic_author_id();
		$output = "";
		
		if( $topic_author == $current_user->ID || current_user_can( 'moderate' )) 
		{
			/*use make_clickable to autolink urls, use the_content filter */
			if(!empty($value))
			{
				$output .= "<div class='bbp_preply_field'><p class='bbp_preply_field_first'>Private Data:</p>".apply_filters('the_content', make_clickable($value) )."</div>";
			}
		}
		
		echo $output;
		
	}
