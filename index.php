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
				<h1 id="timeline-title">Timeline Title</h1>
			</header>
			
			<div id="timeline-loop">
				<div id="timeline-wrapper">
					<?php while ( have_posts() ) : the_post(); ?>
			
						<?php get_template_part( 'sections/entry' ); ?>
				
					<?php endwhile; ?>
				</div>
			</div>
			
			<footer id="timeline-footer"<?php nineline_set_timeline_data(); ?>>
				<span class="date-label">1980</span>
				<span class="date-label">2000</span>
				<span class="date-label">2020</span>
				<span class="date-label">2040</span>
				<span class="date-label">2060</span>
			</footer>
		
		<?php endif; ?>
	</section>

<?php get_footer(); ?>