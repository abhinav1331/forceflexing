<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Add New Notification  
					<a href="<?php echo SITEURL.'admin/viewNotification'; ?>"><button type="button" class="btn btn-outline btn-primary">View All Notification</button>
					</a>
				</h1>
				<?php 
				if(isset($Success))
				{
					echo'<div class="alert alert-success alert-dismissable">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						  <strong>Success!</strong> '.$Success.'.
						</div>';
				}
				elseif(isset($Error))
				{
					echo'<div class="alert alert-warning alert-dismissable">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						  <strong>Warning!</strong>'.$Error.'.
						</div>';
				}
				elseif(isset($error))
				{
					echo'<div class="alert alert-warning alert-dismissable">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
						  foreach($error as $keys):
						  echo '<strong>Warning!</strong>'.$keys.'.<br>';
						  endforeach;
					 echo '	</div>';
				}
				?>			
			<div class="col-lg-8">							
				<form method="post" id="myform">
					<div class="form-group col-lg-12">
						<label for="NotificationTitle">Notification Title</label>
						<input type="text" name="title" <?php if(isset($Fileds['title'])){ echo "value='$Fileds[title]'"; } ?> class="form-control" placeholder="Notification Title">
					</div>
					<div class="form-group col-lg-12">
						<label for="NotificationText">Notification Text</label>
						<input type="text" <?php if(isset($Fileds['message'])){ echo "value='$Fileds[message]'"; } ?> name="message" class="form-control" placeholder="Notification Message">
					</div>			  
					<div class="form-group col-lg-6">
					<label for="NotificationAdd"></label>
						<button type="submit" name="add_notification" class="btn btn-primary col-lg-12 add_noti">Add Notification</button>
					</div>
				</form>						
			</div>
			<div class="col-lg-4">
				<label for="Placeholders">Please Use Below Placeholders</label>
				<p>{Employer Name}</p>
				<p>{Contractor Name}</p>
				<p>{Job Title}</p>
				<p>{User Type}</p>
				<p>{Activity Name}</p>			
			</div>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
	</div>
</div>