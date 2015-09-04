<nav class="navbar navbar-default navbar-fixed-top container-fluid" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Nineline</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      <ul class="nav navbar-nav navbar-right">
      	<?php if(is_user_logged_in()): ?>
      		<li><a href="#" data-toggle="modal" data-target="#myModal">Add Entry <i class="fa fa-plus"></i></a></li>
      	<?php endif; ?>
      	<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Categories <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
				  <?php 
				  $categories = get_categories( 'exclude=1' );
				  
				  foreach($categories as $category): ?>
				  
				  	<li><a href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo $category->name; ?></a></li>
	
				  <?php endforeach; ?> 
			</ul>	 
		</li> 
		<?php if(is_user_logged_in()): ?>
      		<li><a href="#"><i class="fa fa-user"></i></a></li>
      		<li><a href="#">Logout</a></li>
      	<?php else: ?>
      		<li><a href="#" data-toggle="modal" data-target="#info-modal">Register interest</a></li>
      	<?php endif; ?>

        <li><a href="#" data-toggle="modal" data-target="#info-modal"><i class="fa fa-info"></i></a></li>
        <li><a href="http://ninestudios.com/blog/category/nineline/">Blog</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
  
  <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Add Entry</h4>
	      </div>
	      <div class="modal-body">
	        <?php get_template_part( 'sections/add-entry' ); ?>
	      </div>
	    </div>
	  </div>
	</div>
	
  <!-- Modal -->
	<div class="modal fade" id="info-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">About Nineline</h4>
	      </div>
	      <div class="modal-body">
	        <p>Nineline is an online timeline that helps you track past, present and future trends, events and predictions.</p>
	        <p>We're constantly developing Nineline, so sign up to hear the latest.</p>
	        <!-- Begin MailChimp Signup Form -->
			<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
			<style type="text/css">
				#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
				/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
				   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
			</style>
			<div id="mc_embed_signup">
			<form action="//charliejackson.us4.list-manage.com/subscribe/post?u=386543f0f8d9b49a9ad6440a1&amp;id=b46a7dcfa3" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			    <div id="mc_embed_signup_scroll">
				<h2>Subscribe to our mailing list</h2>
			<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
			<div class="mc-field-group">
				<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
			</label>
				<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
			</div>
			<div class="mc-field-group">
				<label for="mce-FNAME">First Name </label>
				<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
			</div>
			<div class="mc-field-group">
				<label for="mce-LNAME">Last Name </label>
				<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
			</div>
				<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
			    <div style="position: absolute; left: -5000px;"><input type="text" name="b_386543f0f8d9b49a9ad6440a1_b46a7dcfa3" tabindex="-1" value=""></div>
			    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
			    </div>
			</form>
			</div>
			<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
			<!--End mc_embed_signup-->
	      </div>
	    </div>
	  </div>
	</div>
</nav>