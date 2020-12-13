<?php

function myhome_child_enqueue_styles() {
	$options           = get_option( 'myhome_redux' );
	$dependency_parent = array();
	$dependency_child  = array( 'myhome-style' );
	if ( ! is_rtl() ) {
		$parent_style = '/style.min.css';
	} else {
		$parent_style = '/style-rtl.min.css';
	}
	if ( ! isset( $options['mh-performance_css'] ) || empty( $options['mh-performance_css'] ) ) {
		$dependency_parent[] = 'normalize';
		$dependency_child[]  = 'normalize';
		if ( ! is_rtl() ) {
			$parent_style = '/style.css';
		} else {
			$parent_style = '/style-rtl.css';
		}
	}

	wp_enqueue_style( 'myhome-style', get_template_directory_uri() . $parent_style, $dependency_parent, My_Home_Theme()->version );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', $dependency_child, My_Home_Theme()->version );
    wp_enqueue_style( 'custom-style-rtl', get_stylesheet_directory_uri() . '/custom-style-rtl.css', $dependency_child, My_Home_Theme()->version );

}

add_action( 'wp_enqueue_scripts', 'myhome_child_enqueue_styles' );

function myhome_lang_setup() {
	load_child_theme_textdomain( 'myhome', get_stylesheet_directory() . '/languages' );
}

add_action( 'after_setup_theme', 'myhome_lang_setup' );