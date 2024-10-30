<?php
/*
Plugin Name: Block Pattern Maker
Plugin URI: https://github.com/rebelwageslave/block-pattern-maker/
Description: Create your own block patterns and use them.
Version: 2.0
Author: rebelwageslave
Author URI: http://github.com/rebelwageslave/
License: GPL2
*/


function dk_block_pattern_maker_init() {

	register_post_type( 'dk_block_patterns', array(
		'label'               => __( 'Block patterns', 'block-pattern-maker' ),
		'public'              => true,
		'show_in_rest'        => true,
		'exclude_from_search' => true,
		'menu_icon'           => 'dashicons-grid-view',
		'taxonomies'          => array( 'category' )
	) );

	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$posts = get_posts(
		array(
			'post_type'   => 'dk_block_patterns',
			'numberposts' => - 1,
			'post_status' => 'publish'
		)
	);

	foreach ( $posts as $post ) {
		if ( ! $post->post_content ) {
			continue;
		}
		$categories = get_the_category( $post->ID );


		$category_names = array();
		foreach ( $categories as $category ) {
			$category_names[] = 'dk_' . $category->slug;
			/**
			 * @var $category WP_Term
			 */
			register_block_pattern_category( 'dk_' . $category->slug, array( 'label' => __( $category->name, 'dk_block_patterns' ) ) );
		}

		register_block_pattern( 'dk-' . md5( $post->post_title ),
			array(
				'title'       => $post->post_title,
				'description' => $post->post_title,
				'content'     => $post->post_content,
				'categories'  => $category_names
			) );
	}

}


add_action( 'init', 'dk_block_pattern_maker_init' );