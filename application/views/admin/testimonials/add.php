<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
			<?php if(isset($data) && $data['segment'] != "add"){
				
				echo'<h1 class="page-header">Edit Testimonials "'.$data['clientName'].'"</h1>';
				
			}else{
				
				echo'<h1 class="page-header">New Testimonials</h1>';
				
			} 	

			if(isset($Msg) && $Msg == 1)
			{	
				echo '<div class="alert alert-success alert-dismissable">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>Success!</strong> Content Updated.
					</div>';	
			}
			elseif(isset($Msg) && $Msg != 1)
			{
				echo '<div class="alert alert-danger alert-dismissable">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <strong>Warning!</strong> Some Error Occur Please Try Again.
				</div>';
			}			
			?>
				
				<form method="POST">
					<div class="col-lg-9">
						<div class="form-group">
							<label>Client Name</label>
							<?php if(isset($error['ClientName'])): echo '<span class="error">'.$error["ClientName"].'</span>'; endif; ?>
							<input type="text" <?php if(isset($data)){	echo"value='".$data['clientName']."'";	} ?>  name="clientName" class="form-control" placeholder="Enter Client Name">
							
						</div>						
						<div class="form-group">
							<label>Content</label>
							<?php if(isset($error['Content'])): echo '<span class="error">'.$error["Content"].'</span>'; endif; ?>
							<textarea name="content" rows="10" class="form-control"><?php if(isset($data)){	echo $data['content'];	} ?></textarea>												
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label>Rating</label>
							<?php if(isset($error['Rating'])): echo '<span class="error">'.$error["Rating"].'</span>'; endif; ?>
							<select name="rating" class="form-control" >
								<option value="">Select Rating</option>								
								<option <?php if(isset($data) && $data['rating'] == '1'){	echo "Selected";	} ?> value="1">1</option>								
								<option <?php if(isset($data) && $data['rating'] == '2'){	echo "Selected";	} ?> value="2">2</option>								
								<option <?php if(isset($data) && $data['rating'] == '3'){	echo "Selected";	} ?> value="3">3</option>								
								<option <?php if(isset($data) && $data['rating'] == '4'){	echo "Selected";	} ?> value="4">4</option>								
								<option <?php if(isset($data) && $data['rating'] == '5'){	echo "Selected";	} ?> value="5">5</option>								
							</select>
						</div>
						<div class="form-group">
						<?php 
						if(isset($data) && $data['segment'] != "add")
						{
							echo'<input type="submit" name="update" value="Update"  class="btn btn-primary" >';	
						} 
						else
						{
								echo'<input type="submit" name="save" value="Create"  class="btn btn-primary" >';
						}
						
						
						?>
						
						</div>	
					</div>		
				<form>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->