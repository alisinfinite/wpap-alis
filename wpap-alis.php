<?php
/*
Plugin Name: WordPress ActivityPub - Alis's Edits
Plugin URI: https://alis.me/
Description: Alis's shortcode edit for the WordPress ActivityPub plugin. Shortcode to use is <code>[wpap_alis_content length="2500"]</code>. <code>length</code> is optional, and is how long a fulltext post can be.
Version: 0.1
Author: Alis
Author URI: http://alis.me/
*/

// die if called directly
if(!function_exists('add_action')){
	echo 'No sweetie...';
	exit;
}

// init 
add_action('init', 'wpap_alis_init');
function wpap_alis_init() {
	add_shortcode('wpap_alis_content', 'wpap_alis_shortcode_func');
}


// the code i guess...
function wpap_alis_shortcode_func($atts){
	
	$atts = shortcode_atts(array(
		'length' => 2500,
	), $atts, 'wpap_alis_content');
	
	$post = get_post();
	$content = get_the_content('Continue reading ›', false, $post->ID);
	$content = apply_filters('the_content', $content);
	$title = '<p><a href="'. get_permalink($post->ID) .'">'. get_the_title( $post->ID ) .'</a></p>';
	
	// custom thing for the Mt. TBR plugin to always show titles on book reviews
	$pID = get_post_parent();
	$content = ($pID && get_post_type($pID) == 'book') ? $title . $content : $content;
	
	// change the '2500' to whatever you like
	// this is basically how long (in characters) a fulltext post can be before it switches
	// over to using only the excerpt
	if(strlen($content) > $atts['length']) {
		$content = $title .'<p>'. wp_strip_all_tags(get_the_excerpt(), true) .'</p>';
	}

	return $content;
}


?>