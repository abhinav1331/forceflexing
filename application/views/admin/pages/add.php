<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
			<?php 			
			if(isset($errors))
			{
				if(isset($errors))
				{
					echo'<div class="alert alert-warning alert-dismissable">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
					foreach($errors as $keys):					
						 echo'<li><strong>Warning!</strong>'.$keys.'</li>';
					endforeach;
					echo'</div>';
				}
			} 
			?>
			
			
			<?php if(isset($data)){
				
				echo'<h1 class="page-header">Edit Page "'.$data['title'].'"</h1>';
				
			}else{
				
				echo'<h1 class="page-header">Add New Page</h1>';
				
			} ?>
				
				<form method="POST" id="myform" enctype="multipart/form-data">
					<div class="col-lg-9">
						<div class="form-group">
							<label>Page Title</label>
							<input type="text" <?php if(isset($data)){	echo"value='".$data['title']."'";	} ?>  name="page_title" class="form-control" placeholder="Enter Page Title">
						</div>
						<div class="form-group">
							<label>Banner Tag Line</label>
							<input type="text" <?php if(isset($data)){	echo"value='".$data['tag_line']."'";	} ?>  name="tag_line" class="form-control" placeholder="Enter Banner Tag Line">
						</div>
						<div class="form-group">	
							<label>Banner Tag Line Position</label>
							<select class="form-control" name="banner_float">
								<option value="">Select Banner Tagline Position</option>
								<option value="left-top" <?php if(isset($data) && $data['tag_line_float'] == "left-top" ){	echo "selected";	} ?> >Float Banner Tagline Left Top</option>
								<option value="left-bottom" <?php if(isset($data) && $data['tag_line_float'] == "left-bottom" ){	echo "selected";	} ?>>Float Banner Tagline Left Bottom</option>
								<option value="right-bottom" <?php if(isset($data) && $data['tag_line_float'] == "right-bottom" ){	echo "selected";	} ?>>Float Banner Tagline Right Bottom</option>
								<option value="right-top" <?php if(isset($data) && $data['tag_line_float'] == "right-top" ){	echo "selected";	} ?>>Float Banner Tagline Right Top</option>
							</select>
						</div>						
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
							<label>Banner Image</label>
							<input name="banner_image" type='file' onchange="readURL(this);" />	
							<img style="max-width: 250px; max-height: 250px;" id="blah" src="<?php if(isset($data) && $data['banner_image'] != "" ){	echo BASE_URL.'/static/uploads/pages/'.$data['banner_image'];	} ?>" alt="your image" />
							<?php if(isset($data['banner_image']) && $data['banner_image'] != ""){		echo "<input type='hidden' name='preloaded' value='".$data['banner_image']."'>";		} ?>
						</div>
						<div class="form-group">
						<?php 
						if(isset($data))
						{
							echo'<input type="submit" name="update" value="Update"  class="btn btn-primary" >';	
						} 
						else
						{
								echo'<input type="submit" name="save" value="Create"  class="btn btn-primary Create" >';
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
