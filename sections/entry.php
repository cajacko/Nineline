<?php global $post; ?>
<?php if( nineline_update_globals() ): ?>
	
	<article class="entry<?php nineline_the_entry_classes(); ?>"<?php nineline_the_data(); ?><?php //nineline_test_positions( 750, 1000 ); ?>>
		<h3 class="entry-title"><a href=""><?php the_title(); ?></a></h3>
		<div class="entry-line"></div>
	</article>
	
<?php endif; ?>