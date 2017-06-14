  <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add User 
							<a href="<?php echo SITEURL.'admin/viewUsers'; ?>"><button type="button" class="btn btn-outline btn-primary">View All User</button>
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
						?>						
						<form method="post" id="myform">
							<div class="form-group col-lg-6">
							<label for="exampleInputEmail1">First Name</label>
							<input type="text" <?php if(isset($Fileds['first_name'])){ echo "value='$Fileds[first_name]'"; } ?> name="first_name" class="form-control" placeholder="First Name">
							<?php if(isset($errors['First Name'])){ ?><p class="text-danger"> <?php echo $errors['First Name']; ?> </p><?php } ?>
						  </div>
						  <div class="form-group col-lg-6">
							<label for="exampleInputPassword1">Last Name</label>
							<input type="text" name="last_name" <?php if(isset($Fileds['last_name'])){ echo "value='$Fileds[last_name]'"; } ?> class="form-control" placeholder="Last Name">
							<?php if(isset($errors['Last Name'])){ ?><p class="text-danger"> <?php echo $errors['Last Name']; ?> </p><?php } ?>
						  </div>
						  <div class="form-group col-lg-6">
							<label for="exampleInputEmail1">Email address</label>
							<input type="email" name="email" <?php if(isset($Fileds['email'])){ echo "value='$Fileds[email]'"; } ?> class="form-control" placeholder="Email">
							<?php if(isset($errors['Email'])){ ?><p class="text-danger"> <?php echo $errors['Email']; ?> </p><?php } ?>
						  </div>
						  <div class="form-group col-lg-6">
							<label for="company">Company Name</label>
							<input type="text" name="company" <?php if(isset($Fileds['company'])){ echo "value='$Fileds[company]'"; } ?> class="form-control" placeholder="Company Name">
							<?php if(isset($errors['Company'])){ ?><p class="text-danger"> <?php echo $errors['Company']; ?> </p><?php } ?>
						  </div>
						  <div class="form-group col-lg-6">
							<label for="exampleInputPassword1">Password</label>
							<div class="input-group">
							  <input type="text" class="form-control" <?php if(isset($Fileds['password'])){ echo "value='$Fileds[password]'"; } ?> name="password" placeholder="Password">
							  <span class="input-group-btn">
								<button class="btn btn-info gnt_pwd" type="button" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Generating.." >Generate Password!</button>
							  </span>
							</div>
							<?php if(isset($errors['password'])){ ?><p class="text-danger"> <?php echo $errors['password']; ?> </p><?php } ?>
							
						  </div>
						  <div class="form-group col-lg-6">
							<label for="exampleInputPassword1">Username</label>							
							<div class="input-group">
							  <input type="text" name="username" <?php if(isset($Fileds['Username'])){ echo "value='$Fileds[Username]'"; } ?> class="form-control" placeholder="Username">
							  <span class="input-group-btn">
								<button class="btn btn-primary gnt_usr" type="button" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Generating.." >Generate Username!</button>
							  </span>
							</div>
							<?php if(isset($errors['Username'])){ ?><p class="text-danger"> <?php echo $errors['Username']; ?> </p><?php } ?>
						  </div>
						<div class="form-group col-lg-6">
							<label for="exampleInputPassword1">Country</label>
							<select name="country" class="form-control">
							  <?php if(isset($options)){ echo $options; } ?>							  
							</select>
							<?php if(isset($errors['Country'])){ ?><span class="text-danger"> <?php echo $errors['Country']; ?> </span><?php } ?>
						  </div>
						  <div class="form-group col-lg-6">
							<label for="exampleInputPassword1">Select User Profile</label>
							<select name="role" class="form-control">
								<option value="">Select Role</option>
								<option value="2">Employer</option>
								<option value="3">Contractor</option>
							</select>
							<?php if(isset($errors['Role'])){ ?><p class="text-danger"> <?php echo $errors['Role']; ?> </p><?php } ?>
						  </div>
							<div class="form-group col-lg-6">
							<label for="exampleInputPassword1"></label>
								<button type="submit" name="register" class="btn btn-primary col-lg-12 add_user">Add User</button>
							</div>
						</form>						
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>		
		<!-- /#page-wrapper -->
	</div>
    <!-- /#wrapper -->