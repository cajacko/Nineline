<?php
/**
 * Remember When functions and definitions.
 *
 * Every version needs this file.
 *
 * @package Remember When
 */
 
 session_start();

add_filter('show_admin_bar', '__return_false');

	function add_header_clacks($headers) {
	    $headers['X-Clacks-Overhead'] = 'GNU Terry Pratchett';
	    return $headers;
	}
	
	add_filter('wp_headers', 'add_header_clacks');

/* -----------------------------
SET FRIST TIME COOKIE
----------------------------- */
	
	$first_visit = false;
	
	if(!is_user_logged_in() && !$_COOKIE['first_visit']) {
		$first_visit = true;
	}
	
	if(!is_user_logged_in()) {
		$int = 60*60*24*5;
		setcookie("first_visit", 'set', time()+$int);		
	}

/* -----------------------------
ENQUE STYLES AND SCRIPTS
----------------------------- */

	function remember_when_scripts() {
		
		wp_enqueue_script( 'remember-when-bootstrap-script', get_template_directory_uri()  . '/inc/bootstrap/js/bootstrap.min.js', array( 'jquery' )); //Add bootstrap script
		
		wp_enqueue_script( 'remember-when-setup-script', get_template_directory_uri()  . '/js/setup.js', array( 'jquery' )); //Add bootstrap script

		wp_enqueue_style( 'remember-when-bootstrap-style',  get_template_directory_uri()  . '/inc/bootstrap/css/bootstrap.min.css'); //Add bootstrap styl
		
	}
	
	add_action( 'wp_enqueue_scripts', 'remember_when_scripts' );
	

/* -----------------------------
SHOW ALL POSTS IN QUERY
----------------------------- */
	
	function search_filter($query) {
		if ( !is_admin() ) {
			$query->set('posts_per_page', -1 );
		   $query->set('orderby', 'rand' );
		   //$query->set('meta_key', 'start_date_value');
		   //$query->set('meta_value', 20300101);
		   //$query->set('meta_compare', '<');
		   
		   $meta_query = array();
		   
		   if($_GET['start_year'] > $_GET['end_year'] && $_GET['start_year'] && $_GET['end_year']) {

		   } else {
		   		if($_GET['start_year']) {
			   		$start_date = ($_GET['start_year'] * 10000) + 0101;
			   		$meta_query[] = array(
						'key'     => 'start_date_value',
						'value'   => $start_date,
						'compare' => '>'
					);
			   }
			   
			   if($_GET['end_year']) {
			   		$end_date = ($_GET['end_year'] * 10000) + 0101;
			   		$meta_query[] = array(
						'key'     => 'start_date_value',
						'value'   => $end_date,
						'compare' => '<'
					);
			   }
		   }
		   
		   if(!empty($meta_query)) {
		   		$query->set('meta_query', $meta_query);
		   }
		}
	}
	
	add_action('pre_get_posts','search_filter');
	
	
	function nineline_get_date_array($date_type, $article_id) {
		$date = get_post_meta( $article_id, $date_type, true );
		
		if($date) {
			$date_array = explode("-", $date);
			
			$days_since = $date_array[2] + (31 * ($date_array[1] + (12 * $date_array[0])));
			
			return array(
				"date" => $date,
				"year" => $date_array[0],
				"month" => $date_array[1],
				"day" => $date_array[2],
				"days_since" => $days_since
			);
		} else {
			return false;
		}
	}

	
	if($_POST['add_post'] == "yes") {
		$user = get_current_user_id();
		
		if($user == 1) {
			$user = 2;
		}
		
		
		
		if(strtotime($_POST['start_date'])) {
	
			$my_post = array(
			  'post_title'    => $_POST['title'],
			  'post_content'  => $_POST['description'],
			  'post_status'   => 'publish',
			  'post_author'   => $user
			);
			
			$new_post_id = wp_insert_post( $my_post );
			
			update_post_meta($new_post_id, "start_date", $_POST['start_date']);
			if($_POST['author']) {
				update_post_meta($new_post_id, "author", $_POST['author']);
			}
			if($_POST['end_date']) {
				update_post_meta($new_post_id, "end_date", $_POST['end_date']);
			}
			if($_POST['type'] == "innovation") {
				update_post_meta($new_post_id, "innovation", 1);
			}
			if($_POST['type'] == "extinction") {
				update_post_meta($new_post_id, "extinction", 1);
			}
			if($_POST['start_date']) {
				$start_date_value = str_replace("-", "", $_POST['start_date']);
				update_post_meta($new_post_id, "start_date_value", $start_date_value);
			}
			if($_POST['end_date']) {
				$end_date_value = str_replace("-", "", $_POST['end_date']);
				update_post_meta($new_post_id, "end_date_value", $end_date_value);
			}
		}
	}
	
	function nineline_check_post($post_id) {
		if(get_post_meta($post_id, 'start_date', true) == '') {
			wp_delete_post($post_id, true);
		}
	}
	
	//add_action( 'save_post', 'nineline_check_post' );

/* -----------------------------
FIRST VISIT CLASS
----------------------------- */
	
	function nineline_first_visit(){
		global $first_visit;
		
		if($first_visit) {
			echo ' first-visit';
		}
	}
	
/* -----------------------------
ARTICLE POPOVER TEXT
----------------------------- */

	function nineline_popover_text($post_id) {
		$post = get_post($post_id);
		$content = $post->post_content;
		
		if($content != '') {
			echo ' title="'.get_the_title($post_id).'" data-trigger="hover" data-placement="bottom"';
			echo ' data-content="'.substr($content, 0, 100).'... Added by '.get_the_author().'"';
		}
		
	}
	
/* -----------------------------
SHOW KEY
----------------------------- */
	function nineline_show_key() {
		if($_SESSION['close-key'] == 'set') {
			echo ' hidden';
		}
	}