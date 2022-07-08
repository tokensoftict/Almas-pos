<?php 
$url_valid = @get_headers('https://www.google.com');
if (stripos($url_valid[0], '200 OK') !== FALSE ) { ?>
<div class="row">
	<div class="panel">
		<div class="panel-heading">Sync to Online Data</div>
		<div class="panel-body">
			<div class="login-form-area mg-t-30 mg-b-15">
			    <form action="<?php echo base_url('dashboard/sync_data') ?>" method="POST">
			        <button type="submit" name="sync_btn" class="btn btn-success btn-block btn-lg"><i class="fa fa-database"> UPLOAD TO ONLINE</i></button>
			    </form>
			 </div>
		</div>
	</div>
</div>
</div>
<?php } else { ?>
	<div class="row">
		<div class="panel">
			<div class="panel-body">
			<div class="login-form-area mg-t-30 mg-b-15">
			    <h1>No internet connection!</h1>
			 </div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
