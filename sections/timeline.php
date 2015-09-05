<?php
 /**
  * Get's the timeline div and all the entries
  */
?>

<section id="timeline">
	
	<?php if ( have_posts() ) : ?>
	
		<header id="timeline-header">
			
			<h1 id="timeline-title">Timeline Title</h1>
			
		</header>
		
		<div id="timeline-loop">
			
			<div id="timeline-wrapper">
				
				<?php 
					/**
					 * Get all the posts and shuffle them, this keeps 
					 * the query using the normal orderby so that 
					 * pagination will work but the posts will appear 
					 * to load randomly
					 */
					$entries = array();
				
					while ( have_posts() ) {					
						the_post();
						array_push( $entries, get_post() );
					}
				
					shuffle( $entries );
				
					foreach( $entries as $post ) { 
						setup_postdata( $post );
						get_template_part( 'sections/entry' );
					}
				
					wp_reset_postdata(); 
				?>

			</div>
			
		</div>
		
		<footer id="timeline-footer"<?php nineline_the_data( 'footer' ); ?>>
			
			<div id="timeline-footer-wrapper">
				
				<?php nineline_timeline_scale(); ?>
				
			</div>
			
		</footer>
	
	<?php endif; ?>
	
</section>