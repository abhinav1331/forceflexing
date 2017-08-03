<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
			<?php if(isset($data)){
				
				echo'<h1 class="page-header">Edit Page "'.$data['title'].'"</h1>';
				
			}else{
				
				echo'<h1 class="page-header">Add New Page</h1>';
				
			} ?>
				
				<form method="POST">
					<div class="col-lg-9">
						<div class="form-group">
							<label>Page Title</label>
							<input type="text" <?php if(isset($data)){	echo"value='".$data['title']."'";	} ?>  name="page_title" class="form-control" placeholder="Enter Page Title">
						</div>
						<!--<div class="form-group">
							<label>Page Slug</label>
							<div class="input-group">
							  <span class="input-group-addon" id="basic-addon3"><?php echo SITEURL; ?></span>
							 <input type="text" name="page_slug" class="form-control" placeholder="Enter Page Slug">	  
							</div>							
						</div>-->
						<div class="form-group">
							<label>Content</label>
							<textarea name="content" rows="10" class="form-control"><?php if(isset($data)){	echo $data['content'];	} ?></textarea>												
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label>Status</label>
							<select name="status" class="form-control" data-size="5">
								<option <?php if(isset($data) && $data['status'] == "publish" ){	echo "selected";	} ?> value="publish" >Publish</option>
								<option <?php if(isset($data) && $data['status'] == "draft" ){	echo "selected";	} ?> value="draft" >Draft</option>
							</select>
						</div>
						<div class="form-group">
						<?php 
						if(isset($data))
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