  <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Select Country</h1>
						<form method="post">
						<?php 
						if(isset($values)){		$check_CT = json_decode($values['option_value']);		}
						 if(isset($Countries))
						 {
							 foreach($Countries as $keys):
							 if(isset($check_CT))
							 {
								 if(in_array($keys['id'],$check_CT))
									 {
										 echo'<div class="col-sm-3"><label>
												<input type="checkbox" checked name="countries[]" value="'.$keys['id'].'">'.$keys['name'].'</label>
												</div>'; 
										 
									 }
								 else
									 {
										echo'<div class="col-sm-3"><label>
												<input type="checkbox" name="countries[]" value="'.$keys['id'].'">'.$keys['name'].'</label>
												</div>'; 
									 }
							 }
							 else
							 {
								 echo'<div class="col-sm-3"><label>
												<input type="checkbox" name="countries[]" value="'.$keys['id'].'">'.$keys['name'].'</label>
												</div>'; 
									 
							 }
								 
							
							 endforeach;
							 
						 }						
						?>
						<br>					
						<div class="col-sm-12">
						  <input type="submit" class="btn btn-primary col-sm-4 col-sm-offset-4" name="submit" value="Save">
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