<?php
/**
 * Nineline functions and definitions.
 *
 * @package Nineline
 */
	
/* -----------------------------
TERRY PRATCHETT HEADER
----------------------------- */
	/**
	 * Adds a memorial header for Terry Pratchett, 
	 * based off the code in the clacks referrenced 
	 * in the Discworld novel "Going Postal" by 
	 * Terry Pratchett.
	 */
	function add_header_clacks( $headers ) {
	    $headers['X-Clacks-Overhead'] = 'GNU Terry Pratchett'; //Add an array value to the headers variable
	    return $headers; //Return the headers
	}
	
	add_filter( 'wp_headers', 'add_header_clacks' );
	
/* -----------------------------
SETUP THEME
----------------------------- */	
	add_filter('show_admin_bar', '__return_false'); //Always hide admin bar

/* -----------------------------
ADD STYLES AND SCRIPTS
----------------------------- */
	function nineline_scripts() {
		/*
		 * Add the bootstrap stylesheet and JavaScript
		 */
		wp_enqueue_style( 'nineline-bootstrap-style',  get_template_directory_uri()  . '/inc/bootstrap/css/bootstrap.min.css' );
		wp_enqueue_script( 'nineline-bootstrap-script', get_template_directory_uri()  . '/inc/bootstrap/js/bootstrap.min.js', array( 'jquery' ) );
		
		/*
		 * Add the template.js file which provides global functions used by other JavaScript files.
		 */
		wp_enqueue_script( 'nineline-template-script', get_template_directory_uri()  . '/js/template.js', array( 'jquery' ) );
		
		/*
		 * Add the core setup.js file which is used on every page.
		 */
		wp_enqueue_script( 'nineline-setup-script', get_template_directory_uri()  . '/js/setup.js', array( 'jquery' ) );
	}
	
	add_action( 'wp_enqueue_scripts', 'nineline_scripts' );
	
/* -----------------------------
REGISTER TIMELINE ENTRIES
----------------------------- */
	/**
	 * Register entries
	 * Register entry categories
	 */
	function nineline_register_entry_post_type() {
		$args = array(
	      'public' => true,
	      'label'  => 'Entries',
	      'supports' => array( 'title', 'editor', 'custom-fields', 'revisions' )
	    );
	    
	    register_post_type( 'entry', $args );
	    
	    register_taxonomy(
			'entry-category',
			'entry',
			array(
				'label' => __( 'Entry Categories' ),
				'hierarchical' => true,
			)
		);
	}
	
	add_action( 'init', 'nineline_register_entry_post_type' );
	
/* -----------------------------
FILTER ENTRIES
----------------------------- */
	function exclude_category( $query ) {
	    $query->set( 'post_type', 'entry' );
	    $query->set( 'posts_per_page', -1 );
	}
	
	add_action( 'pre_get_posts', 'exclude_category' );

/* -----------------------------
DEFINE GLOBAL VARS AND GLOBAL FUNCTIONS
----------------------------- */	
	global $earliest_entry_date, $earliest_entry_value, $latest_entry_date, $latest_entry_value;
	
	function nineline_update_globals() {
		global $earliest_entry_date, $earliest_entry_value, $latest_entry_date, $latest_entry_value;
		
		$start_date = get_post_meta( get_the_ID(), 'start_date', true );
		$start_date_value = get_post_meta( get_the_ID(), 'start_date_value', true );

		if( $start_date != '' && $start_date_value != '' ) {
			if( ( !isset( $earliest_entry_date ) || !isset( $earliest_entry_value ) || !isset( $latest_entry_date ) || !isset( $latest_entry_value ) )) {
				$earliest_entry_date = $start_date;
				$earliest_entry_value = $start_date_value;
				$latest_entry_date = $start_date;
				$latest_entry_value = $start_date_value;
			} else {
				if( $earliest_entry_value > $start_date_value ) {
					$earliest_entry_date = $start_date;
					$earliest_entry_value = $start_date_value;
				}
				
				if( $latest_entry_value < $start_date_value ) {
					$latest_entry_date = $start_date;
					$latest_entry_value = $start_date_value;
				}
			}

			$end_date = get_post_meta( get_the_ID(), 'end_date', true );
			$end_date_value = get_post_meta( get_the_ID(), 'end_date_value', true );
			
			if( $end_date == '' || $end_date_value == '' ) {
				$end_date = $start_date;
				$end_date_value = $start_date_value;
			}
			
			if( !isset( $latest_entry_date ) || !isset( $latest_entry_value ) ) {
				$latest_entry_date = $end_date;
				$latest_entry_value = $end_date_value;
			} elseif( $latest_entry_value < $end_date_value ) {
				$latest_entry_date = $end_date;
				$latest_entry_value = $end_date_value;
			}
			
			return true;
		} else {
			return false;
		}
	}
	
	function nineline_set_timeline_data() {
		global $earliest_entry_date, $earliest_entry_value, $latest_entry_date, $latest_entry_value;
		
		if( isset( $earliest_entry_date ) && isset( $earliest_entry_value ) && isset( $latest_entry_date ) && isset( $latest_entry_value ) ) {
			echo ' data-earliest-date="' . $earliest_entry_date . '"';
			echo ' data-earliest-value="' . $earliest_entry_value . '"';
			echo ' data-latest-date="' . $latest_entry_date . '"';
			echo ' data-latest-value="' . $latest_entry_value . '"';
		} else {
			return false;
		}
	}