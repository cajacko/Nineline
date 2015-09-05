<?php
 /**
  * Get's footer navigation/toolbar
  *
  * Uses the default Bootstrap Navbar
  */
?>

<nav class="navbar navbar-default navbar-fixed-bottom">
	<div class="container-fluid">
		<form class="form-inline" method="get">   
			<ul class="nav navbar-nav">
				<li class="pull-left">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-calendar"></i> Start</div>
							
							<select class="form-control" name="start_year">
								<?php
									$continue = true;
									$count = 0;
									$period = 5;
									$default = 1950;
									
									if($_GET['start_year']) {
										$default = $_GET['start_year'];
									}
									
									while($continue):
										$year = 1900 + ($period * $count);
										$next_year = $year + ($period * ($count + 1));
									?>
										
										<option <?php if($default >= $year && $default < $next_year): echo 'selected'; endif; ?>><?php echo $year; ?></option>
									
									<?php
										if($count > 39) {
											$continue = false;
										}
									
										$count++;
									endwhile;
								?>
							</select>
						</div>
					</div>
				</li>
				
				<li class="pull-right">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-calendar"></i> End</div>
							
							<select class="form-control" name="end_year">
								<?php
									$continue = true;
									$count = 0;
									$period = 5;
									$default = 2050;
									
									if($_GET['end_year']) {
									$default = $_GET['end_year'];
									}
									
									while($continue):
										$year = 1900 + ($period * $count);
										$next_year = $year + ($period * ($count + 1));
									?>
									
										<option <?php if($default >= $year && $default < $next_year): echo 'selected'; endif; ?>><?php echo $year; ?></option>
									
									<?php
										if($count > 39) {
											$continue = false;
										}
									
										$count++;
									endwhile;
								?>
							</select>
						</div>
					</div>
				</li>
				
				<li id="filter"><button class="btn btn-default" type="submit" href="#">Go</button></li>      				        
			</ul>
		</form>
	</div>
</nav>