<div id="loading">
	<h2>Loading...</h2>
</div>

<div id="timeline">
	<div id="timeline-loop">
		<div id="timeline-container">
			<?php
			if ( have_posts() ) :
				
				$post_loop = array();
				
				while ( have_posts() ) : the_post(); 
				
					$start_date = nineline_get_date_array("start_date", get_the_ID());
					$post_loop[get_the_ID()] = $start_date["days_since"];
					
				endwhile;
				
				asort($post_loop);
				
				foreach($post_loop as $article_id => $value) :
				
				?>
				
					<?php
					$start_date = nineline_get_date_array("start_date", $article_id);
					$end_date = nineline_get_date_array("end_date", $article_id);
					
					if(!$end_date) {
						$end_date = $start_date;
					}
					?>
					<article id="post-<?php echo $article_id; ?>">
						<div class="timeline-content<?php if($start_date["days_since"] != $end_date["days_since"]) : echo ' timeline-article-duration'; endif; ?>" data-start-year="<?php echo $start_date["year"]; ?>" data-start-month="<?php echo $start_date["month"]; ?>" data-start-day="<?php echo $start_date["day"]; ?>" data-start-days-since="<?php echo $start_date["days_since"]; ?>" data-end-year="<?php echo $end_date["year"]; ?>" data-end-month="<?php echo $end_date["month"]; ?>" data-end-day="<?php echo $end_date["day"]; ?>" data-end-days-since="<?php echo $end_date["days_since"]; ?>">
							<div class="timeline-article-container">
								<span class="h<?php echo rand(4,6); ?>"><a href="#" data-target="#article-modal-<?php echo $article_id; ?>" data-toggle="modal"<?php nineline_popover_text($article_id); ?>><?php 
									
									if(get_post_meta($article_id, "extinction", true) == 1) {
										echo "<s>";
									} 
									
									echo get_the_title($article_id); 
									
									if(get_post_meta($article_id, "extinction", true) == 1) {
										echo "</s>";
									} 
								
								?></a></span>
							</div>
						</div>
						<?php if($start_date["days_since"] == $end_date["days_since"]) : ?>
							<div class="timeline-article-marker"><div class="timeline-article-marker-ball"></div><div class="timeline-article-marker-line"></div></div>
						<?php else: ?>
							<div class="timeline-article-marker timeline-article-marker-start"><div class="timeline-article-marker-ball"></div><div class="timeline-article-marker-line"></div></div>
							<div class="timeline-article-marker timeline-article-marker-end"><div class="timeline-article-marker-ball"></div><div class="timeline-article-marker-line"></div></div>
							<div class="timeline-article-marker-horizontal"></div>
						<?php endif; ?>
						
						<div class="modal fade" id="article-modal-<?php echo $article_id; ?>" tabindex="-1" role="dialog" aria-labelledby="article-modal-<?php echo $article_id; ?>" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel"><?php echo get_the_title($article_id); ?></h4>
						      </div>
						      <div class="modal-body">
						      	<table class="table table-bordered">
						      		<tr>
						      			<td>Description</td>
								      	<?php
								      	$post = get_post($article_id);
								      	
								      	if($post->post_content != ''): ?>
								      		<td><?php echo apply_filters('the_content', $post->post_content); ?></td>
								      	<?php else: ?>
								      	
								      		<td>This post has no description.</td>
		
								      	<?php endif; ?>
							      	</tr>
							      	<?php if(get_post_meta($article_id, "end_date", true) != ''): ?>
								      	<tr><td>Entry start date</td><td><?php echo date('F j, Y', strtotime(get_post_meta($article_id, "start_date", true))); ?></td></tr>
								      	<tr><td>Entry end date</td><td><?php echo date('F j, Y', strtotime(get_post_meta($article_id, "end_date", true))); ?></td></tr>
								    <?php else: ?>
							      		<tr><td>Entry date</td><td><?php echo date('F j, Y', strtotime(get_post_meta($article_id, "start_date", true))); ?></td></tr>
							      	<?php endif; ?>
							      	<tr><td>Type</td><td>
							      		<?php if(get_post_meta($article_id, "extinction", true) == 1): ?>
							      			Extinction
							      		<?php else: ?>
							      			Innovation
							      		<?php endif; ?>
							      	</td></tr>
							      	<tr><td>Added by</td><td><?php echo get_the_author(); ?></td></tr>
							      	<tr><td>Added on</td><td><?php echo get_the_date(); ?></td></tr>
							      </table>
						      </div>
						    </div>
						  </div>
						</div>
					</article>
	
				<?php endforeach;
				
	
			else :
				
	
			endif;
			?>
		</div>
		<ul id="key" class="list-group <?php nineline_show_key(); ?>">
			<a href="#" id="close-key"><i class="fa fa-times"></i></a>
			<li class="list-group-item"><h5>Innovation</h5> - An event that has/will begin</li>
			<li class="list-group-item"><s>Extinction</s> - An event that has/will end</li>
		</ul>
	</div>
		
	<footer id="timeline-footer">
		<div id="timeline-footer-container">
		</div>
		
	</footer>
		
</div>