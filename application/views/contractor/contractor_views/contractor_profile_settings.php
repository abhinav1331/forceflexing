<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <div class="profile-details">
		  <!--image-->
            <div class="add-avatar">
				<?php 
				if(isset($profile_img) && $profile_img != "")
				{
						$src=BASE_URL.'static/images/contractor/'.$profile_img;
						$class=" filled";
				}
				else
				{
					$src=BASE_URL.'static/images/avatar-icon.png';
					$class="";
				}
				?>
				<div class="avatar-select<?php echo $class;?>"><input type="file" name="contractor_avatar" id="contractor_avatar">
				
				 <img src="<?php echo $src;?>" id="previewHolder" class="avatar-set"/></div>
			</div>
			
            <div class="add-personal-details">
              <h2 class="pro-title">
				  <?php if(isset($first_name)) echo $first_name; ?> 
				<?php if(isset($last_name)) echo $last_name; ?>
			  </h2>
			  
			  <!--skills-->
			  <div class="skills-main">
				  
				  <p class="pro-skills">
					<?php
						if(isset($skills) && $skills!="")
						{
							$skills_array=unserialize($skills);
							foreach($skills_array as $s)
								echo '<span class="industry-tag">'.$s.'</span>';
						}
					?>
                      <br>
                      <a href="#" data-toggle="modal" data-target="#skill"data-toggle="modal" data-target="#skill"><?=(isset($skills) && $skills!="") ?'Edit':'Add'?> Skill</a>
				  </p>
                  
			  </div>
			  <!--skills end-->
			  
			 <!--locations start-->
			 <div class="loc-main">
				 
				 <p class="pro-location">
					<?php if(isset($location) && $location != "") 
						{
							$locations=unserialize($location);
							$i=0;
							foreach($locations as $loc)
							{
								if($i==0)
									echo $loc.',';
								else
									echo $loc;
								$i++;
							}
						}
						?>
                      <br>
                 <a href="#" id="add_location_poup" data-toggle="modal" data-target="#location"><?=(isset($location) && $location != "") ?'Edit':'Add'?> Location</a>
				  </p>
                
              
			</div>
			  <!--locations end-->
			  
			  <!--Add speciality-->
			  <div class="speciality-main">
				
				<p class="pro-speciality">
						<?php if(isset($speciality) && $speciality != "")
							{ ?>
								<span class="industry-tag"><?php  echo $speciality;?></span>
					<?php  }
						?>
                    <br><a href="#"  data-toggle="modal" data-target="#speciality"><?=(isset($speciality) && $speciality != "") ?'Edit':'Add'?> Speciality</a>
				</p>
                 
			  </div>
			  <!--Speciality Ends-->
			  
			  
			  <!-- Industries start-->
			  <div class="indus-main">
				  
				  <p class="pro-industries">
						<?php 
						if(isset($industries) && $industries != "")
						{
							$ind=explode(',',$industries);
							foreach($ind as $i)
							{
								?>
								<span class="industry-tag"><?php  echo $i;?></span>
								<?php
							}
						}
						?>
                      <br>
                      <a href="#"  data-toggle="modal" data-target="#industries"><?=(isset($industries) && $industries != "") ?'Edit':'Add'?> Industries</a>
				  </p>
                
			  </div>
			  <!-- Industries end-->
			  
			</div>
          </div>
          <div class="add-more-details">
            <h3>Overview</h3>
            <textarea name="" id="overview" cols="" rows="" placeholder="Add Overview"><?php if(isset($description) && $description!="") echo $description; ?></textarea>
            <h3>Employment History</h3>
			<!-- Employemnt History-->
              <div class="pd-btn-group">
			<a href="javascript:void(0);" class="btn-gray pull-left" id="employment_history">Add new</a>
			<a href="javascript:void(0);" class="btn-blue pull-right" id="save_emp_history">Save</a>
                  </div>
              <div class="clearfix"></div>
			<?php if(isset($employment_history) && $employment_history !="")
					$employment=unserialize($employment_history);
				if(!empty($employment))
				{
					foreach($employment as $e)
					{
					?>
						<div class="row employment">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<input type="text" value="<?php echo $e[0]; ?>" name="designation" id="designation" class="form-control input" placeholder="Designation">
								</div>
								<div class="col-md-6">
									<input type="text" value="<?php echo $e[1]; ?>" name="company_name" id="company_name" class="form-control input" placeholder="Company's Name">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<input type="text"  placeholder="From" value="<?php echo $e[2]; ?>" class="from_eh form-control input">
								</div>
								
								<div class="col-md-6">
									<input type="text"  placeholder="To" value="<?php echo $e[3]; ?>" class="to_eh form-control input">
									<a class="remove-employment" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
				}	
				else
				{
					?>
					<div class="row employment">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<input type="text" name="designation" id="designation" class="form-control input" placeholder="Designation">
								</div>
								<div class="col-md-6">
									<input type="text" name="company_name" id="company_name" class="form-control input" placeholder="Company's Name">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<input type="text" placeholder="From"  class="from_eh form-control input">
								</div>
								
								<div class="col-md-6">
									<input type="text" placeholder="To"  class="to_eh form-control input">
									<a class="remove-employment" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					</div>
	 	<?php   }?>
			<!--Emploment history ends-->
			<!--education-->
            <h3>Education</h3> 
              <div class="pd-btn-group">
           <a href="javascript:void(0);" class="btn-gray pull-left" id="education">Add new</a>
		   <a href="javascript:void(0);" class="btn-blue pull-right" id="save_education">Save</a>
               </div>
              <div class="clearfix"></div>
		   <?php 
				if(isset($education) && $education!="")
					$education_array=unserialize($education);
			?>
			<?php 
				if(!empty($education_array))
				{
					foreach($education_array as $e)
					{
					?>
						<div class="row education">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
										<input type="text"  value="<?php echo $e[0]; ?>" name="qualification" id="qualification" class="form-control input" placeholder="Qualification">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										<select name="from-edu" id="from-edu" class="form-control input">		
											<option value="">From</option>
											<?php
												foreach(range(1995, (int)date("Y")-1) as $qyear)
												{
													$selected=($e[1] == $qyear)?'selected':'';
													echo "<option value='".$qyear."' ".$selected.">".$qyear."</option>";
												}						
											?>
										</select>
									</div>
									<div class="col-md-6">
										<select name="to-edu" id="to-edu" class="form-control input">		
											<option value="">To</option>
											<?php
												foreach(range(1995, (int)date("Y")) as $qtoyear)
												{
													$selected=($e[2] == $qtoyear)?'selected':'';
													echo "<option value='".$qtoyear."' ".$selected.">".$qtoyear."</option>";
												}						
											?>
										</select>
										<a class="remove-education" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>
						</div>
			<?php   } 
				}	
				else
				{
				?>
					<div class="row education">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<input type="text" name="qualification" id="qualification" class="form-control input" placeholder="Qualification">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<select name="from-edu" id="from-edu" class="form-control input">		
										<option value="">From</option>
										<?php
											foreach(range(1995, (int)date("Y")-1) as $year)
											{
												echo "\t<option value='".$year."'>".$year."</option>\n\r";
											}						
										?>
									</select>
								</div>
								<div class="col-md-6">
									<select name="to-edu" id="to-edu" class="form-control input">		
										<option value="">To</option>
										<?php
											foreach(range(1995, (int)date("Y")) as $year)
											{
												echo "\t<option value='".$year."'>".$year."</option>\n\r";
											}						
										?>
									</select>
									<a class="remove-education" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					</div>
		  <?php }?>
		  <!--Education ends-->
			
			<!--Training-->
            <h3>Training</h3>
               <div class="pd-btn-group">
           <a href="javascript:void(0);" class="btn-gray pull-left" id="training">Add new </a>
		    <a href="javascript:void(0);" class="btn-blue pull-right" id="save_training">Save</a>
                   
 </div>
              <div class="clearfix"></div>
			<?php
			if(isset($training) && $training!="")
					$training=unserialize($training);
			?>
			<?php if(!empty($training))
			{ 
				foreach($training as $t)
				{
				?>	
					<div class="row training">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<input type="text" value="<?php echo $t[0]; ?>" name="training" id="training" class="form-control input" placeholder="Title">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<select name="from-trai" id="from-trai" class="form-control input">		
										<option value="">From</option>
										<?php
											foreach(range(1995, (int)date("Y")-1) as $year)
											{
												$selected=($t[1] == $year)?'selected':'';
												echo "<option value='".$year."' ".$selected.">".$year."</option>";
											}						
										?>
									</select>
								</div>
								<div class="col-md-6">
									<select name="to-trai" id="to-trai" class="form-control input">		
										<option value="">To</option>
										<?php
											foreach(range(1995, (int)date("Y")) as $year)
											{
												$selected=($t[2] == $year)?'selected':'';
												echo "<option value='".$year."' ".$selected.">".$year."</option>";
											}						
										?>
									</select>
									<a class="remove-training" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
			}
			else
			{
			?>
				<div class="row training">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<input type="" name="training" id="training" class="form-control input" placeholder="Title">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<select name="from-trai" id="from-trai" class="form-control input">		
									<option value="">From</option>
									<?php
										foreach(range(1995, (int)date("Y")-1) as $year)
										{
											echo "\t<option value='".$year."'>".$year."</option>\n\r";
										}						
									?>
								</select>
							</div>
							<div class="col-md-6">
								<select name="to-trai" id="to-trai" class="form-control input">		
									<option value="">To</option>
									<?php
										foreach(range(1995, (int)date("Y")) as $year)
										{
											echo "\t<option value='".$year."'>".$year."</option>\n\r";
										}						
									?>
								</select>
								<a class="remove-training" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
							</div>
						</div>
					</div>
				</div>
	<?php   } ?>
	<!--Training ends-->
          </div>
        </aside>
		
        <aside class="page-sidebar">
          <div class="add-professional-details">
			<div class="hour-main">
				
				<p class="hourly_wage_val"><?php if(isset($hourly_wages) && $hourly_wages!="") echo $hourly_wages. " $/hr "; ?> <br> <a href="#" class="hourly_wage" data-toggle="modal" data-target="#hourly_wage"><?=(isset($hourly_wages) && $hourly_wages != "") ?'Edit':'Add'?> Hourly Wage</a></p>
              
			</div>
            
			<div class="avail-main">
				
				<p class="pro-availability"><?php if(isset($availability) && $availability!="") echo ucfirst($availability); ?><br><a href="#" data-toggle="modal" data-target="#availability" ><?=(isset($availability) && $availability != "") ?'Edit':'Add'?> Availability</a></p>
               
			</div>
			
			<div class="lan-main">
				
				<p class="all-languages">
				<?php  
				if(isset($languages) && $languages!="")
				{
					echo $languages;
				}
				?>
                    <br>
                <a href="#" id="language" data-toggle="modal" data-target="#lang"><?=(isset($languages) && $languages != "") ?'Edit':'Add'?> Languages</a>
				</p>
                
           
			</div>
			
			<div class="type-main">
				
				<p class="freelance-type"><?php if(isset($contractor_type) && $contractor_type != "") echo ucfirst($contractor_type); ?> <br><a href="#" id="contractor-type" data-toggle="modal" data-target="#contrac-type"><?=(isset($contractor_type) && $contractor_type != "") ?'Edit':'Add'?> type</a></p>
           
			</div>
			
            <div><a href="<?php echo BASE_URL;?>contractor/contractor_profile/"  class="view-profile-btn">View profile</a></div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>


<!-- Modal for freeelancer type -->
<div class="modal fade" id="contrac-type" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Add Type</h2>
				<?php 
					if(isset($contractor_type) && $contractor_type != "")
						$type=$contractor_type;
					else
						$type="";
					?>
					<select name="contractor-type" id="type-select" class="input">
						<option value="">Select</option>
						<option value="freelancer" <?php if($type == "freelancer") echo 'selected';?>>Freelancer</option>
						<option value="agency" <?php if($type == "agency") echo 'selected';?> >Agency</option>
					</select>
                <button type="button" class="btn-blue" id="save_contractor_type">Save</button>
			</div>
			
		</div>
    </div>
</div>
<!-- Modal for freelancer ends-->

<!-- Modal for skill -->
<div class="modal fade" id="skill" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                 <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Add Skills</h2>
				<?php if(!empty($skills_array))
				{
					$i=1;
					foreach($skills_array as $skill)
					{
					?>
						<div class="add-skill">
							<input type="text" name="skills[]" value="<?php echo $skill; ?>" class="single-skill input">
							<?php if($i > 1) {?>
								<a class="remove" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
							<?php } ?>
						</div>
			<?php 		$i++;
					}
		        } else 
				{ ?>
					<div class="add-skill">
						<input type="text" name="skills[]" value="" class="single-skill input">
					</div>
	  	<?php   } ?>
                
                
                <button type="button"  class="btn-blue add_skill">Add More</button>
				<button type="button" class="btn-blue" id="save_skills">Save</button>
                
			</div>
		
		</div>
    </div>
 </div>
 
 <!--Modal for Location-->
<div class="modal fade" id="location" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                 <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Add Location</h2>
				<div class="location">
					<div class="row">
						<div class="col-md-6">
						<select id="location-state"  class="input small loc-state">
							   <option value="">Select State</option>
							   <?php 
								$selected_state_id="";
							   foreach($states as $state)
							   {
								   $selected="";
									
								   if(!empty($locations))
								   {
										if($locations[0] == $state['name'])
										{
											$selected='selected';
											$selected_state_id=$state['id'];
										}
									}
									?>
								  <option  value="<?php echo $state['id'];?>" <?php echo $selected; ?>><?php echo $state['name'] ;?></option>
								<?php 
							   }
								?>
							</select>
							<input type="hidden" name="selected-state" id="selected-state" value="<?php echo $selected_state_id; ?>">
						</div>
						<div class="col-md-6">
						<input type="hidden" name="selected-city" id="selected-city" value="<?php echo (!empty($locations))? $locations[1]: ''; ?>">
							<select  id="location-city" class="input small loc-city">
							   <option value="">Select City</option>
							</select>
						</div>
                    </div>
				</div>
                <button type="button" class="btn-blue" id="save_location">Save</button>
			</div>
			
		</div>
    </div>
 </div>
 
 <!--Modal for hourly wage-->
<div class="modal fade" id="hourly_wage" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		
			<div class="modal-body  custom-popup">
                 <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Add Hourly Wage</h2>
				<div class="hourly-wage">
					<input type="text" name="wages" value="<?php if(isset($hourly_wages)) echo $hourly_wages; ?>" class="wages input">$/hr
				</div>
                <button type="button" class="btn-blue" id="save_wages">Save</button>
			</div>
			
		</div>
    </div>
</div>

<!-- Modal for languages-->
<div class="modal fade" id="lang" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Add Languages</h2>
				<input name="contr_language" id="contr_language" class="input" placeholder="Select language" type="text" value="<?php if(isset($languages) && $languages!="")  { echo $languages; }?>">
                <button type="button" class="btn-blue" id="save_lang">Save</button>
			</div>
		
		</div>
    </div>
</div>

<!--Modal for avalaibality-->
<div class="modal fade" id="availability" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                 <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Add Availability</h2>
				<div class="add-availability">
				<?php if(isset($availability) && $availability!='')
						$av=$availability;
					else
						$av="";
					?>
					<select name="availability" id="availability-select" class="input">
						<option value="">Select</option>
						<option value="yes" <?php echo ($av == "yes")?'selected':''; ?>>Yes </option>
						<option value="no" <?php echo ($av == "no")?'selected':''; ?>>No</option>
					</select>
				</div>
                <button type="button" class="btn-blue"  id="save_avail">Save</button>
			</div>
			
		</div>
    </div>
</div>

<!-- Modal for Industries-->
<div class="modal fade" id="industries" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Add Industries</h2>
				<div class="industries">
					<input id="indus" name="indus" id="indus" class="input" value="<?php if(isset($industries) && $industries != "") echo $industries;?>">
				</div>
                <button type="button" class="btn-blue"  id="save_industries">Save</button>
			</div>
			
		</div>
    </div>
</div>

<!---modal for speciality-->
<div class="modal fade" id="speciality" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body custom-popup">
                <a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                <h2>Add Speciality</h2>
				<div class="speciality">
				<?php if(isset($speciality) && $speciality != "")
						$spec=$speciality;
					else
						$spec="";
					?>
					<select name="speciality" id="speciality-val" class="form-control input">
						<?php if(isset($all_speciality) && !empty($all_speciality))
						{
							foreach($all_speciality as $speciality)
							{
								?>
								<option value="<?=$speciality['category_name'];?>" <?php if($spec == $speciality['category_name']) echo 'selected';?>> <?=$speciality['category_name'];?></option>
					  <?php }
						}
						?>
					</select>
				</div>
                
                <button type="button" class="btn-blue" id="save_speciality">Save</button>
			</div>
			
		</div>
    </div>
</div>

<style>
 .ui-datepicker-calendar {
    display: none;
    }
</style>