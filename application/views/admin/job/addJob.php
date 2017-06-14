  <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add New Job</h1>
						<ul class="nav nav-tabs">
						  <li class="active"><a data-toggle="tab" href="#home">Job Description</a></li>
						  <li><a data-toggle="tab" href="#menu1">Job Activites</a></li>
						  <li><a data-toggle="tab" href="#menu2">Job Location</a></li>
						  <li><a data-toggle="tab" href="#menu2">Payment and Additional information</a></li>
						</ul>

						<div class="tab-content">
						  <div id="home" class="tab-pane fade in active">							
							  <div class="form-group">
								<label for="email">Job Name:</label>
								<input type="text" name="Job_name" class="form-control" >
							  </div>
							   <div class="form-group">
								<label for="email">Job Description:</label>
								<input type="textarea" name="Job_desc" class="form-control txtEditor" >
							  </div> 
							  <div class="form-group">								
								  <input class="input-file" id="my-file" type="file" name="fileUpload">					 
								  <code class="file-return"></code>
								  <p>The file can be up to 5 mb in size.</p>          
							  </div>
							   <div class="form-group">
								<label for="email">How many employees do you need to hire for this job?:</label>
								<div class="radio">
								  <label><input type="radio" name="optradio">One</label>
								</div>
								<div class="radio">
								  <label><input type="radio" name="optradio">Multiple</label>
								</div>				 
							  </div>					 
						  </div>
						  <div id="menu1" class="tab-pane fade">
							<h3>Menu 1</h3>
							<p><button class="add_field_button">Add More Fields</button></p>
							<p>Some content in menu 1.</p>
							<div class="">
								<table class="table table-responsive">
						<tbody><tr>
						  <th class="col-md-3" scope="row">Activites Name:</th>
						  <td>
							<div class="row">
								<div class="col-md-9">
								  <input name="jp_activity_name[]" id="jp_activity_name" type="text" class="form-control input small half-width">
								</div>
							</div>
						</td>
						</tr>
						<tr>
						  <th scope="row">Select:</th>
						  <td>
							<label class="radio-custom">
							  <input type="radio" name="jp_start_stop_time1[]" value="fixed" id="jp_start_stop_time_fix" checked="">
							</label>  
							<label class="radio-custom">
							  <input type="radio" name="jp_start_stop_time1[]" value="flexible" id="jp_start_stop_time_flex">
							</label>
						</td>
						</tr>
						<tr>
						  <th scope="row">Fixed:</th>
						  <td><div class="row">
							  <div class="col-md-6">
								<div class="row">
								  <div class="col-md-6">
									<input type="text" name="jp_act_start_date[]" id="jp_act_start_date" class="form-control start datePickStart" placeholder="start date" data-grp="1">
								  </div>
								  <div class="col-md-6">
									<input type="text" name="jp_act_start_time[]" id="jp_act_start_time" class="form-control input small watch-icon jp_act_start_time ui-timepicker-input" placeholder="start time" autocomplete="off">
								  </div>
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="row">
								  <div class="col-md-6">
									<input type="text" name="jp_act_end_date[]" id="jp_act_end_date" class="form-control end datePickEnd" placeholder="finish date" data-grp="1">
								  </div>
								  <div class="col-md-6">
									<input type="text" name="jp_act_end_time[]" id="jp_act_end_time" class="form-control input small watch-icon jp_act_start_time ui-timepicker-input" placeholder="finish time" autocomplete="off">
								  </div>
								</div>
							  </div>
							</div>
						</td>
						</tr>
						<tr>
						  <th scope="row">Enter address:</th>
						  <td><div class="row">
							  <div class="col-md-6">
								<div class="row">
								<div class="col-md-6">                                
									<select onchange="onchangeState(this);" data-attribute="activityReunion1" name="jp_act_state[]" id="sel12 stateId" class="form-control input small states">
									   <option value="">Select State</option>
									 </select>
								  </div>
								   <div class="col-md-6">
									<select onchange="ourChange(this)" name="jp_act_city[]" id="sel13 cityId" class="form-control input small cities">
									   <option value="">Select City</option>
									</select>
								  </div>
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="row">
								  <div class="col-md-6">
										  <input type="text" class="form-control input small" name="jp_act_street[]" id="jp_act_street" placeholder="street">
									<!--<select name="" class="input small">
									  <option>street</option>
									</select>-->
								  </div>
								  <div class="col-md-6">
									<input type="text" class="form-control input small" name="jp_act_zip[]" id="jp_act_zip" placeholder="zip" onkeyup="isValidPostalCode(this)">
									<div class="messahe-zip"></div>
								  </div>
								</div>
							  </div>
							</div></td>
						</tr>
						<tr>
						  <th scope="row">Name contact:</th>
						  <td><div class="row">
							  <div class="col-md-6">
								<input name="jp_act_cont_fname[]" id="jp_act_cont_fname" type="text" placeholder="first name" class="form-control input small">
							  </div>
							  <div class="col-md-6">
								<input name="jp_act_cont_lname[]" id="jp_act_cont_lname" type="text" placeholder="last name" class="form-control input small">
							  </div>
							</div></td>
						</tr>
						<tr>
						  <th scope="row">Contact:</th>
						  <td><div class="row">
							  <div class="col-md-6">
								<input name="jp_act_cont_phne[]" id="jp_act_cont_phne" type="number" placeholder="Phone" class="form-control input small">
							  </div>
							  <div class="col-md-6">
								<input name="jp_act_cont_email[]" id="jp_act_cont_email" type="email" placeholder="E-mail" class="form-control input small">
							  </div>
							</div></td>
						</tr>
						<tr>
						  <th scope="row">Notes/tasks:</th>
						  <td><textarea class="form-control" name="jp_act_notes[]" rows="5" id="comment"></textarea></td>
						</tr>
					  </tbody></table>
								</div>
						  </div>
						  <div id="menu2" class="tab-pane fade">
							<h3>Menu 2</h3>
							<p>Some content in menu 2.</p>
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