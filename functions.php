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
	    
		$meta_query = array();
		
		if( ( $_GET['start_year'] && !isset( $_GET['end_year'] ) || ( $_GET['start_year'] <= $_GET['end_year'] && isset( $_GET['start_year'] ) && isset( $_GET['end_year'] ) ) ) ) {
			$start_date = ( $_GET['start_year'] * 10000 ) + 0101;
			
			$meta_query[] = array(
				'key'     => 'start_date_value',
				'value'   => $start_date,
				'compare' => '>',
			);
		}
	
		if( ( $_GET['end_year'] && !isset( $_GET['start_year'] ) || ( $_GET['start_year'] <= $_GET['end_year'] && isset( $_GET['start_year'] ) && isset( $_GET['end_year'] ) ) ) ) {
			$end_date = ( $_GET['end_year'] * 10000 ) + 0101;
			
			$meta_query[] = array(
				'key'     => 'start_date_value',
				'value'   => $end_date,
				'compare' => '<'
			);
		}
		
		if( !empty( $meta_query ) ) {
			$query->set('meta_query', $meta_query);
		}
	}
	
	if( !is_admin() ) {
		add_action( 'pre_get_posts', 'exclude_category' );
	}

/* -----------------------------
DEFINE GLOBAL VARS AND GLOBAL FUNCTIONS
----------------------------- */	
	global $earliest_entry_date, $earliest_entry_value, $latest_entry_date, $latest_entry_value;
	global $current_start_date, $current_start_value, $current_end_date, $current_end_value;
	
	function nineline_update_globals() {
		global $earliest_entry_date, $earliest_entry_value, $latest_entry_date, $latest_entry_value;
		global $current_start_date, $current_start_value, $current_end_date, $current_end_value;
		
		$start_date = get_post_meta( get_the_ID(), 'start_date', true );
		$start_date_value = get_post_meta( get_the_ID(), 'start_date_value', true );

		if( $start_date != '' && $start_date_value != '' ) {
			$current_start_date = $start_date;
			$current_start_value = $start_date_value;
			$current_end_date = $start_date;
			$current_end_value = $start_date_value;
			
			if( ( !isset( $earliest_entry_date ) || !isset( $earliest_entry_value ) || !isset( $latest_entry_date ) || !isset( $latest_entry_value ) ) ) {
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
			
			if( $end_date != '' && $end_date_value != '' ) {
				$current_end_date = $end_date;
				$current_end_value = $end_date_value;
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
			unset( $current_start_date );
			unset( $current_start_value );
			unset( $current_end_date );
			unset( $current_end_value );
			
			return false;
		}
	}
	
	function nineline_the_data( $type = 'entry' ) {
		global $earliest_entry_date, $latest_entry_date, $current_start_date, $current_end_date;
		
		if( $type == 'entry' && isset( $current_start_date ) && isset( $current_end_date ) ) {
			$start_date = $current_start_date;
			$end_date = $current_end_date;
		} elseif( isset( $earliest_entry_date ) && isset( $latest_entry_date ) ) {
			$start_date = $earliest_entry_date;
			$end_date = $latest_entry_date;
		} else {
			return false;
		}
		
		nineline_echo_date_data( $start_date, 'start' );
		nineline_echo_date_data( $end_date, 'end' );
	}
	
	function nineline_date_data( $date ) {
		$date_array = explode("-", $date);	
		$days_since = $date_array[2] + ( 31 * ( $date_array[1] + ( 12 * $date_array[0] ) ) );
		
		$array = array(
			'year' => $date_array[0],
			'month' => $date_array[1],
			'day' => $date_array[2],
			'days_since' => $days_since,
		);
		
		return $array;
	}
	
	function nineline_the_entry_classes() {
		global $current_start_date, $current_start_value, $current_end_date, $current_end_value;
		
		$invention_extinction = get_post_meta( get_the_ID(), 'type', true );
		
		if( isset( $current_start_value ) && isset( $current_end_value ) && $current_start_value != $current_end_value ) {
			echo ' entry_is_duration';
		}
		
		if( $invention_extinction != '' ) {
			echo ' ' . $invention_extinction;
		}
	}
	
	function nineline_the_entry_title_classes() {
		$rand = rand( 0, 10 );
		
		if( $rand < 2 ) {
			echo ' big';
		} elseif ( $rand < 6 ) {
			echo ' medium';
		}
	}
	
	function nineline_echo_date_data( $date, $type) {
		$date_vars = nineline_date_data( $date );
		
		echo ' data-'. $type .'-year="' . $date_vars['year'] . '"';
		echo ' data-'. $type .'-month="' . $date_vars['month'] . '"';
		echo ' data-'. $type .'-day="' . $date_vars['day'] . '"';
		echo ' data-'. $type .'-days-since="' . $date_vars['days_since'] . '"';
	}
	
	function nineline_test_positions( $max_left, $max_top ) {
		$left = rand( -100, $max_left );
		$top = rand( -100, $max_top );
		
		echo ' style="left: ' . $left . 'px; top: ' . $top . 'px;"';	
	}
	
	function nineline_echo_date_label( $date, $title, $classes ) {
		$days_since = nineline_date_data( $date );
		echo '<div class="date-label ' . $classes .'">';
		echo '<span class="date-label-title" data-start-days-since="' . $days_since['days_since'] . '" data-end-days-since="' . $days_since['days_since'] . '">';
		echo $title; 
		echo '</span>';
		echo '<div class="date-label-mark timeline-line" data-start-days-since="' . $days_since['days_since'] . '" data-end-days-since="' . $days_since['days_since'] . '"></div></div>';
	}
	
	function nineline_timeline_scale() {
		global $earliest_entry_date, $latest_entry_date;
		
		$end_date_data = nineline_date_data( $latest_entry_date );
		$start_date_data = nineline_date_data( $earliest_entry_date );
		
		$range_days = $end_date_data['days_since'] - $start_date_data['days_since'];
		$range_years = $range_days / 365;
		$range_months = $range_days / 12;
		$label_array = array();
		
		if( $range_years > 100 ) {
			for( $i = $start_date_data['year']; $i <= $end_date_data['year']; $i++ ) {
				if($i % 20 == 0) {
					$label_array[] = array (
						'title' => $i,
						'date' => $i . '-01-01',
					);
				}
			}
		}
		
		$count = 1;
		$length = count( $label_array );	
		
		foreach( $label_array as $label ) {
			$classes = nineline_label_display_rules( $length, $count );
			
			nineline_echo_date_label( $label['date'], $label['title'], $classes );
			
			$count++;
		}	
	}
	
	function nineline_label_display_rules( $length, $count ) {
		$length_1 = array(
			'1' => '',	
		);
		
		$length_8 = array(
			1 => '',
			2 => 'extra-small-hide mobile-hide medium-hide',
			3 => 'extra-small-hide',
			4 => 'extra-small-hide mobile-hide medium-hide',
			5 => 'extra-small-hide mobile-hide medium-hide',
			6 => 'extra-small-hide',
			7 => 'extra-small-hide mobile-hide medium-hide',
			8 => '',	
		);
		
		$var_string = 'length_' . $length;		
		$array = $$var_string;
		
		if( isset( $array[$count] ) ) {
			return $array[$count];
		} else {
			return false;
		}
	}