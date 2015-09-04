<?php
/**
 * The header for the Nineline theme.
 *
 * Displays all of the <head> section and everything up till <div id="main-wrap">
 *
 * @package Nineline
 */
 
 	//Used to load posts for an infinite scroll effect
	if( $_GET['action'] == 'load_posts' ){
		get_template_part( 'sections/timeline-loop' ); //Skip all the head info and load only the posts html
		exit; //Prevent any other html or scripts from rendering
	} 
?>

	<!DOCTYPE html>
	<html lang="en-GB" id="html" data-home-url="<?php echo home_url( '/' ); ?>">
	
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="author" content="Charlie Jackson">
			<meta property="og:description" content="<?php bloginfo( 'description' ); ?>" />
			<meta id="less-vars">
			<title><?php wp_title( '|', true, 'right' ); ?></title>
			<link rel="author" href="http://charliejackson.com">
			<link rel="profile" href="http://gmpg.org/xfn/11">
			<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/inc/media/favicon.ico" />
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/font-awesome/css/font-awesome.min.css">
	
			<?php wp_head(); ?>
			
		</head>
	
		<body>			
			<main>