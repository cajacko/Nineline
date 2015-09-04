<form class="add-entry form-horizontal container-fluid" method="POST">
	<div class="form-group">
		<input class="form-control" type="text" name="title" placeholder="Title">
	</div>
	<div class="form-group">
		<textarea class="form-control" rows="5" name="description" placeholder="Description..."></textarea>
	</div>
	<div class="form-group">
		<input class="form-control" type="date" name="start_date">
	</div>
	<div class="form-group">
		<input class="form-control" type="date" name="end_date">
	</div>
	<div class="form-group">
		<input class="form-control" type="text" name="author" placeholder="Author">
	</div>
	<div class="form-group">
		<div class="radio">
		  <label>
		    <input type="radio" name="type" id="optionsRadios1" value="innovation">
		    Innovation
		  </label>
		</div>
		<div class="radio">
		  <label>
		    <input type="radio" name="type" id="optionsRadios2" value="extinction">
		    Extinction
		  </label>
		</div>
	</div>
	<div class="form-group">
		<input type="hidden" name="add_post" value="yes">
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
</form>