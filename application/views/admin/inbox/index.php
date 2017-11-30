  <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Inbox</h1>	
							<div class="col-lg-8">
							<h2>Chating With : <span class="name_user"></span>
							<span style="display:none"class="loader"><img src="http://force.imarkclients.com/static/images/loading.gif" width="20px" height="20px"></span>
							</h2>
								<div style="display:block;
    width:100%;
    height:150px;    
    overflow:scroll;" class="chat-ajax">
									
								</div>
								<div class="textarea">
								<form method="POST">
									<input type="hidden" name="fromUserId" value="<?php echo $_SESSION['user_id']; ?>" >
									<input type="hidden" name="toUserId" value="" >
									<input type="hidden" name="ConversionId" value="" >
									<textarea data-emojiable="true" id="message" class="col-lg-9" rows="5"></textarea>
									<div class="col-lg-3">
										<div id="id_dropzone" class="dropzone upload-files-cover"></div>
									</div>
									<button class="btn btn-primary send_me" > Send </button>
								</form>
								</div>
								<div style="overflow:visible; display:none;" class="progress">
									<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
									<span id="percent">0</span>% <span id="percent_text">Uploading</span>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="col-lg-4">
								<ul class="nav nav-tabs">
									<li class="active"><a data-toggle="tab" href="#home">Recent Chats</a></li>
									<li><a data-toggle="tab" href="#contactlist">Contact List</a></li>
								</ul>
								<div class="tab-content">
								  <div id="home" class="tab-pane fade in active">
									<h3>Recent Chats</h3>
									<?php if(isset($recentChatList)): echo $recentChatList; endif;  ?>
								  </div>
								  <div id="contactlist" class="tab-pane fade">
								  <p>
								  <select class="form-control" id="filter_list">
									<option value="">Filter List</option>
									<option value="5">Admin</option>
									<option value="2">Employer</option>
									<option value="3">Contractor</option>
								  </select>
								  </p>
								  <?php if(isset($contactlist)): echo $contactlist; endif;  ?>
								  </div>							  
								</div>							
							</div>
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
	