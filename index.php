<?php
/**
 * The main template file.
 *
 * @package Nineline
 */
?>

<?php get_header(); ?>
	
	<section id="timeline">
		<?php if ( have_posts() ) : ?>
			<header id="timeline-header">
				<a href="" id="prev">Prev</a><a id="load-more" href=""><h1 id="timeline-title">Timeline Title</h1></a><a href="" id="next">next</a>
			</header>
			
			<div id="timeline-loop">
				<div id="timeline-wrapper">
					<?php $entries = array(); ?>
					
					<?php while ( have_posts() ) : the_post(); ?>
			
						<?php array_push( $entries, get_post() ); ?>
				
					<?php endwhile; ?>
					
					<?php shuffle( $entries ); ?>
					
					<?php foreach( $entries as $post ): setup_postdata( $post ); ?>
						
						<?php get_template_part( 'sections/entry' ); ?>
					
					<?php endforeach; ?>
					
					<?php wp_reset_postdata(); ?>
	
				</div>
			</div>
			
			<footer id="timeline-footer"<?php nineline_the_data( 'footer' ); ?>>
				<div id="timeline-footer-wrapper">
					<?php nineline_timeline_scale(); ?>
				</div>
			</footer>
		
		<?php endif; ?>
	</section>

<?php get_footer(); ?>