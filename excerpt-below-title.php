<?php
/*
Plugin Name: Excerpt Below Title
Plugin URI: https://github.com/Kuuak/excerpt-below-title
Description: Move the excerpt metabox below the post title
Version: 1.0.1
Author: Kuuak
Author URI: https://profiles.wordpress.org/kuuak
License: GPLv2
*/
/**
 * Plugin Name:		Excerpt Below Title
 * Description: 	Move the excerpt metabox below the post title
 * Author: 				Felipe Paul Martins
 * Version: 			1.0.1
 * Author URI:		https://github.com/Kuuak
 * License:				GPL-2.0+
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Excerpt Below Title is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * Excerpt Below Title is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package		Scramble_Email
 * @author		Felipe Paul Martins
 * @license		GPL-2.0+
 * @link			https://github.com/Kuuak/excerpt-below-title
 */

/* Prevent loading this file directly */
defined( 'ABSPATH' ) || exit;

/**
 * Removes the regular excerpt box. We're not getting rid
 * of it, we're just moving it above the wysiwyg editor
 *
 * @return null
 */
function exbt_remove_normal_excerpt() {
	remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
}
add_action( 'admin_menu' , 'exbt_remove_normal_excerpt' );

/**
 * Add the excerpt meta box back in with a custom screen location
 *
 * @param  string $post_type
 * @return null
 */
function exbt_add_excerpt_meta_box( $post_type ) {
	if ( in_array( $post_type, array( 'post' ) ) ) {
		add_meta_box(
			'exbt_postexcerpt',
			__( 'Excerpt' ),
			'post_excerpt_meta_box',
			$post_type,
			'exbt_after_title',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'exbt_add_excerpt_meta_box' );


/**
 * You can't actually add meta boxes after the title by default in WP so
 * we're being cheeky. We've registered our own meta box position
 * `after_title` onto which we've regiestered our new meta boxes and
 * are now calling them in the `edit_form_after_title` hook which is run
 * after the post tile box is displayed.
 *
 * @return null
 */
function exbt_run_after_title_meta_boxes() {
		global $post, $wp_meta_boxes;
		# Output the `exbt_after_title` meta boxes:
		do_meta_boxes( get_current_screen(), 'exbt_after_title', $post );
}
add_action( 'edit_form_after_title', 'exbt_run_after_title_meta_boxes', 99 );


function exbt_admin_styles( $hook ) {

	// Only enqueue for blog post admin screen
	if ( 'post.php' == $hook ) {
		wp_enqueue_style( 'exbt_admin_post_stylesheet',  plugins_url( 'exbt-style.css', __FILE__ ) );
	}
}
add_action( 'admin_enqueue_scripts', 'exbt_admin_styles' );
