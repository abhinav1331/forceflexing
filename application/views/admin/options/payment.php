<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Stripe Gateway Keys</h1>
				<?php if(isset($errors)): ?>
				<div class="errors">
				<?php  
				
				foreach( $errors as $keys  ): 
				echo '<div class="alert alert-danger">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Warning!</strong> '.$keys.'.
					</div>';
				
				endforeach; 
				?>
				</div>
				<?php endif; ?>
				
				
				
				<form method="POST">
				<h2> Test Credentials</h2>
					<div class="form-group">
						<input type="hidden" name="id" class="form-control" value="<?php if(isset($Fileds['id'])){ echo $Fileds['id'];  } ?>">
						<label>Test Publishable key</label>
						<input type="password" value="<?php if(isset($Fileds['pb_key'])){ echo $Fileds['pb_key'];  } ?>"name="pb_key" class="form-control" placeholder="Publishable key">
					</div>
					<div class="form-group">
						<label>Test Secret key</label>
						<input type="password" name="sk_key" value="<?php if(isset($Fileds['sk_key'])){ echo $Fileds['sk_key'];  } ?>" class="form-control" placeholder="Secret key">
					</div>	
					<h2> Live Credentials</h2>					
					
					<div class="form-group">
						<input type="hidden" name="id" class="form-control" value="<?php if(isset($Fileds['id'])){ echo $Fileds['id'];  } ?>">
						<label>Live Publishable key</label>
						<input type="password" value="<?php if(isset($Fileds['Lpb_key'])){ echo $Fileds['Lpb_key'];  } ?>"name="Lpb_key" class="form-control" placeholder="Publishable key">
					</div>
					<div class="form-group">
						<label>Live Secret key</label>
						<input type="password" name="Lsk_key" value="<?php if(isset($Fileds['Lsk_key'])){ echo $Fileds['Lsk_key'];  } ?>" class="form-control" placeholder="Secret key">
					</div>
					<div class="form-group">
						<label>Enable Live Mode</label>
						<input type="checkbox" <?php if(isset($Fileds['live_mode']) && $Fileds['live_mode'] != ''){ echo "checked"; } ?> name="live_mode" data-toggle="toggle">
					</div>	
					<div class="col-lg-offset-10">
						<input type="submit" name="save" class="btn btn-primary form-control" value="Save">
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