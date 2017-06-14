  <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Jobs</h1>
						<?php 
						/* echo"<pre>";
						print_r($_SERVER['REQUEST_URI']);
						echo"</pre>"; */
						echo $Details;
						/* echo"<pre>";
						print_R($Details);
						echo"</pre>"; */
						?>
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
	
	
	<!-- Modal Pop-->
	<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php if(isset($title)){	echo $title;	}else{	echo"No Information available";	} ?></h4>
      </div>
      <div class="modal-body">
		  <div class="row">
			  <div class="col-lg-8">
			  <?php  if(isset($content)){	echo $content;	}else{	echo "Please Try Again";  } ?>	
			  </div>
			  <div class="col-lg-4">
			  <?php if(isset($profile)){ print_R($profile); }else{	echo"No Profile Information Available";	} ?>
			  </div>
		  </div>
      </div>
	  
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
	