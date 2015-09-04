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
			<header>
			</header>
			
			<div id="timeline-loop">
				<?php while ( have_posts() ) : the_post(); ?>
		
					<?php get_template_part( 'sections/entry' ); ?>
			
				<?php endwhile; ?>
			</div>
			
			<footer<?php nineline_set_timeline_data(); ?>>
			</footer>
		
		<?php endif; ?>
	</section>

<?php get_footer(); ?>