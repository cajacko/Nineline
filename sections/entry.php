<?php
 /**
  * Get's the individual timeline entry
  */
?>

<?php global $post; ?>

<?php if( nineline_update_globals() ): ?>
	
	<article class="entry not-processed not-shown<?php nineline_the_entry_classes(); ?>"<?php nineline_the_data(); ?>>
		
		<div class="entry-wrap">
			
			<h3 class="entry-title<?php nineline_the_entry_title_classes(); ?>">
				
				<a href=""><?php the_title(); ?></a>
				
			</h3>
			
			<div class="entry-markers">
				
				<div class="entry-line">
					
					<div class="entry-ball"></div>
					
				</div>
				
			</div>
			
		</div>
		
	</article>
	
<?php endif; ?>