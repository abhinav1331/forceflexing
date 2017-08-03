 <main role="main">
        <section class="page-wrap companyProfile">
            <div class="container">
                <div class="page-main">
					<?php if(isset($success) && !empty($success)) {?>
						<div class="success"><?php echo $success;?></div>
					<?php } ?>
                    <aside class="main-page-body">
                        <h2>Company profile / setting Information:</h2>
                        <form id="company_profile" name="company_profile" method="POST" action="">
							<input type="hidden" id="emp_id" name="emp_id" value="<?php echo $comp_id;?>">
							
							<div class="companyInfo">
								 <h3>Profile Image</h3>
								<div class="profile-details">
									<div class="add-avatar">
										<?php if(isset($company_details['company_image']) && !empty($company_details['company_image']))
										{
											$class=" filled";
											$url=BASE_URL."static/images/employer/".$company_details['company_image'];
										}
										else
										{
											$class="";
											$url=BASE_URL."static/images/avatar-icon.png";
										}?>
										<div class="avatar-select<?php echo $class ?>">
											<input type="file" value="" id="empavselect">
											<input name="employer_avatar" id="employer_avatar" type="hidden" value="">
											<img src="<?php echo $url; ?>" id="previewHolderEmp" class="avatar-set">
										</div>
									</div>
								</div>
							</div>
							
                            <div class="companyInfo">
                                <h3>Company Info </h3>
                                <div class="row">
                                    <div class="form-group col-sm-7">
                                        <div class="formElements">
                                            <div class="formLabel">
                                                <p>Company Name</p>
                                            </div>
                                            <div class="formFields">
                                                <input name="company_name" id="company_name" type="text" class="input" value="<?php echo (!empty($userdata) && isset($userdata['company_name']))? $userdata['company_name'] :"";?>"> </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formLabel">
                                                <p>Company Address</p>
                                            </div>
                                            <div class="formFields">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                       <select name="company_country" id="company_country" class="input">
															<?php echo $instance->options->show_countries($userdata['country']);?>
														</select>
													</div>
													<div class="col-md-6">
                                                       <!-- <input name="company_state"  id="company_state" type="text" class="input" placeholder="State">-->
														 <select name="company_state" id="company_state" class="input">
															<?php
															if(isset($company_details['company_state']) && !empty($company_details['company_state']))
																echo $instance->get_states($userdata['country'],$company_details['company_state']);
															else
																echo $instance->get_states($userdata['country']);
															?>
														</select>
													</div>
													<div class="col-md-6">
														<select name="company_city" id="company_city" class="input">
															
															<?php if(isset($company_details['company_city']) && !empty($company_details['company_city'])) 
															{
																echo $instance->get_cities($company_details['company_state'],$company_details['company_city']);
															}
															else
															{
																?>
																<option value="">Select City</option>
																<?php
															}
															?>
														</select>
													</div>
													<div class="col-md-6">
                                                        <input name="company_zip" id="company_zip" type="text" class="input" placeholder="Zip" value="<?=(isset($company_details['company_zip']) && !empty($company_details['company_zip']))? $company_details['company_zip'] : '';?>"> 
													</div>
												</div>
                                            </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formLabel">
                                                <p>Contact Information</p>
                                            </div>
                                            <div class="formFields">
												<?php 
													$company_email='';
													if(isset($userdata['email']) and !empty($userdata['email'])){		
													$company_email=$userdata['email'];	
													}
													?> 
													
                                                <input name="company_email" id="company_email" type="email" class="input" value="<?php echo $company_email;?>" placeholder="Email"> </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formLabel">
                                                <p>Company Information</p>
                                            </div>
                                            <div class="formFields">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input name="business_desc" id="business_desc" type="text" class="input" placeholder="Business Description" value="<?=(isset($company_details['company_busi_desc']) && !empty($company_details['company_busi_desc']))? $company_details['company_busi_desc'] : '';?>"> </div>
                                                    <div class="col-md-6">
                                                        <input name="company_website" id="company_website" type="text" class="input" placeholder="Website" value="<?=(isset($company_details['company_website']) && !empty($company_details['company_website']))? $company_details['company_website'] : '';?>"> </div>
                                                    <div class="col-md-12">
                                                        <input name="company_link"  id="company_link" type="text" class="input" placeholder="About us Link" value="<?=(isset($company_details['company_about_us']) && !empty($company_details['company_about_us']))? $company_details['company_about_us'] : '';?>"> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formLabel">
                                                <p>Contact VAT</p>
											</div>
                                            <div class="formFields">
                                                <input name="vat" id="vat" type="text" class="input" placeholder="" value="<?=(isset($company_details['contact_vat']) && !empty($company_details['contact_vat']))? $company_details['contact_vat'] : '';?>"> </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <h4>Company Contact</h4>
                                        <div class="formElements">
                                            <div class="formFields">
                                                <div class="row">
                                                    <div class="col-md-6">
													<?php 
													$firstname='';
													if(isset($userdata['first_name']) and !empty($userdata['first_name'])){		
													$firstname=$userdata['first_name'];	
													}
													?> 
													
                                                        <input name="company_first_name" id="company_first_name" type="text" class="input" placeholder="First Name" value="<?php echo $firstname; ?>"> </div>
                                                    <div class="col-md-6">
													<?php 
													$last_name='';
													if(isset($userdata['last_name']) and !empty($userdata['last_name']))
													{		
														$last_name=$userdata['last_name'];	
													}
													?> 
                                                        <input name="company_last_name" id="company_last_name" value="<?php echo $last_name; ?>" type="text" class="input" placeholder="Last Name"> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formFields">
												<input name="company_address_1" id="company_address_1" type="text" class="input" placeholder="Address 1" value="<?=(isset($company_details['company_add_1']) && !empty($company_details['company_add_1']))? $company_details['company_add_1'] : '';?>"> 
											 </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formFields">
                                                <input name="company_address_2" id="company_address_2" type="text" class="input" placeholder="Address 2" value="<?=(isset($company_details['company_add_2']) && !empty($company_details['company_add_2']))? $company_details['company_add_2'] : '';?>"> 
											</div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formFields">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input name="company_mobile_phone" id="company_mobile_phone" type="text" class="input" placeholder="Mobile Phone" value="<?=(isset($company_details['company_mobile_phone']) && !empty($company_details['company_mobile_phone']))? $company_details['company_mobile_phone'] : '';?>"> </div>
                                                    <div class="col-md-6">
                                                        <input name="company_landline" id="company_landline" type="text" class="input" placeholder="Landline" value="<?=(isset($company_details['company_landline']) && !empty($company_details['company_landline']))? $company_details['company_landline'] : '';?>"> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formFields">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input name="company_linkedin_link" id="company_linkedin_link" type="text" class="input" placeholder="LinkedIn Link" value="<?=(isset($company_details['company_linkedin']) && !empty($company_details['company_linkedin']))? $company_details['company_linkedin'] : '';?>"> </div>
                                                    <div class="col-md-6">
                                                        <input name="company_facebook_link" id="company_facebook_link" type="text" class="input" placeholder="Facebook Link" value="<?=(isset($company_details['company_fb']) && !empty($company_details['company_fb']))? $company_details['company_fb'] : '';?>"> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formFields">
												<input type="text" name="company_industries" placeholder="Add Industries" id="company_industries" class="input industries" value="<?=(isset($company_details['company_indus']) && !empty($company_details['company_indus']))? $company_details['company_indus'] : '';?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="billingMethod">
                                <h3>Billing Method </h3>
                                <div class="methodRow">
                                    <div class="bmType"><img src="<?php echo BASE_URL; ?>static/images/bank-icon.png" alt="Bank"></div>
                                    <div class="bmDetails">
                                        <input name="company_bank_account" id="company_bank_account" type="text" class="input" placeholder="Bank Account"> </div>
                                    <div class="bmAction"> <a href="#" class="btn btn-blue btn-small">Edit</a></div>
                                </div>
								
								<!--Add Details for bank account-->
								<div class="methodRow" id="bank_account_details" style="display:none">
									<div class="bmDetails edit-row">
										 <div class="row">
											  <div class="col-md-4">
												<input name="account_holder_name" id="account_holder_name" type="text" class="input" placeholder="Account Holder Name"> 
											  </div>
																			 
											 <div class="col-md-4">
												<input name="account_number" id="account_number" type="text" class="input" placeholder="Bank Account">
											 </div>
												
											 <div class="col-md-4">
												<input name="ifsc_code" id="ifsc_code" type="text" class="input" placeholder="<?=($userdata['country']=="US") ?'ABA Number': 'SWIFT/BIC code';?>"> 
											 </div>
																			 
											 <div class="col-md-4">
												<input name="bank_name" id="bank_name" type="text" class="input" placeholder="Bank Name"> 
											 </div>
																			 
											 <div class="col-md-4">
												<input name="bank_address" id="bank_address" type="text" class="input" placeholder="Bank Address">
											 </div>
																			 
											 <div class="col-md-4">
												<input name="optional_code" id="optional_code" type="text" class="input" placeholder="<?=($userdata['country']=="US") ?'SWIFT/BIC code': 'Routing Code';?>">			  
											 </div>
										</div>    
									</div>
								</div>
								
                                <div class="methodRow">
                                    <div class="bmType"><img src="<?php echo BASE_URL; ?>static/images/credit-card-icon.png" alt="Credit Card"></div>
                                    <div class="bmDetails">
                                        <input name="company_credit_account" id="company_credit_account" type="text" class="input" placeholder="Credit Card"> </div>
                                    <div class="bmAction"> <a href="#" class="btn btn-blue btn-small">Edit</a></div>
                                </div>
								
								<!--Add Details for Credit Card-->
								<div class="methodRow" id="credit_details" style="display:none">
									<div class="bmDetails edit-row">
										 <div class="row">
											  <div class="col-md-4">
												<input name="card_number" id="card_number" type="text" class="input" placeholder="Card Number"> 
											  </div>
																			 
											 <div class="col-md-4">
												<input name="card_fname" id="card_fname" type="text" class="input" placeholder="First Name">
											 </div>
												
											 <div class="col-md-4">
												<input name="card_lname" id="card_lname" type="text" class="input" placeholder="Last Name"> 
											 </div>
																			 
											 <div class="col-md-4">
												<input name="expiry_date" id="expiry_date" type="text" class="input" placeholder="Expiry Date"> 
											 </div>
																			 
											 <div class="col-md-4">
												<input name="cvv" id="cvv" type="text" class="input" placeholder="CVV">
											 </div>
										</div>    
									</div>
								</div>
								
								
                                <div class="methodRow">
                                    <div class="bmType"><img src="<?php echo BASE_URL; ?>static/images/paypal-icon.png" alt="Paypal"></div>
                                    <div class="bmDetails">
                                        <input name="company_paypal" id="company_paypal" type="text" class="input" placeholder="Paypal"> </div>
                                    <div class="bmAction"> <a href="#" class="btn btn-blue btn-small">Edit</a></div>
                                </div>
                                <div class="autoPayStatus">
                                    <div class="autoPlayHeading">
                                        <p>Auto Pay Status </p>
                                    </div>
                                    <div class="autoPlayInput">
                                        <label>
                                            <input type="checkbox" name="autopay_status" id="autopay_status" checked data-toggle="toggle"> Primary</label>
                                    </div>
                                    <div class="autoPlayBtn"> <a href="#" id="add_new_payment_gateway" class="btn btn-blue btn-small">Add New</a> </div>
                                </div>
                            </div>
                            <div class="passwordChange">
                                <h3>Password</h3>
                                <div class="row">
                                    <div class="form-group col-sm-5">
                                        <div class="formElements">
                                            <div class="formLabel">
                                                <p>Old Password</p>
                                            </div>
                                            <div class="formFields">
                                                <input name="employer_old_password" id="employer_old_password" type="password" class="input" placeholder="********"> </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formLabel">
                                                <p>New Password</p>
                                            </div>
                                            <div class="formFields">
                                                <input name="employer_new_password" id="employer_new_password" type="password" class="input" placeholder="********">
                                                <p class="helperText">A strong password is over 12 characters and combination of letters (upper and lower case) and numbers</p>
                                            </div>
                                        </div>
                                        <div class="formElements">
                                            <div class="formLabel">
                                                <p>Re-enter Password</p>
                                            </div>
                                            <div class="formFields">
                                                <input name="employer_confirm_password" id="employer_confirm_password" type="password" class="input" placeholder="********"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="securityQuestions">
                                <h3>Security Question </h3>
                                <div class="row">
                                    <div class="form-group col-sm-6">
									<?php if(isset($company_details['security_ques']) && !empty($company_details['security_ques'])) 
											{
												$selected_question= $company_details['security_ques'];
												$display= 'display:block';
											}
										 else
										 {
											  $selected_question="";
											  $display= 'display:none';  
										 }
									?>
                                        <div class="exisiting_ques" style="<?=$display;?>">
											<div class="formElements">
												<div class="formLabel">
													<p>Existing Question</p>
												</div>
												<div class="formFields">
													<select name="employer_existing_question" id="employer_existing_question" class="input">
														<option value="">Select Question</option>
														<option value="1" <?php if($selected_question == "1") echo "selected"; ?>>Question 1</option>
														<option value="2" <?php if($selected_question == "2") echo "selected"; ?>>Question 2</option>
														<option value="3" <?php if($selected_question == "3") echo "selected"; ?>>Question 3</option>
													</select>
												</div>
											</div>
											<div class="formElements">
												<div class="formLabel">
													<p>Answer to Question</p>
												</div>
												<div class="formFields">
													<input type="password" name="employer_existing_answer" id="employer_existing_answer" class="input" value="<?php if(isset($ans) && !empty($ans)) echo $ans; ?>" placeholder="********">
												</div>
											</div>
										</div>
										<div class="new_ques">
											<div class="formElements">
												<div class="formLabel">
													<p>New Question</p>
												</div>
												<div class="formFields">
													<?php $edit=(isset($company_details['security_ques']) && !empty($company_details['security_ques'])) ? '_edit' :''; ?>
													<select name="employer_new_question<?=$edit;?>" id="employer_new_question<?=$edit;?>" class="input">
														<option value="">Select Question</option>
														<option value="1">Question 1</option>
														<option value="2">Question 2</option>
														<option value="3">Question 3</option>
													</select>
												</div>
											</div>
											<div class="formElements">
												<div class="formLabel">
													<p>Answer</p>
												</div>
												<div class="formFields">
													<input type="password" name="employer_new_answer<?=$edit;?>" id="employer_new_answer<?=$edit;?>" cols="" rows="" class="input" placeholder="********">
													<p class="rememberThisOption">
														<label for="rememberThis" class="custom-checkbox">
															<input id="rememberThis" name="employer_remember" <?php echo (isset($ans) && !empty($ans)) ?  "checked": ""; ?> id="employer_remember"  type="checkbox"> <span class="custom-check"></span> Remember this computer</label>
													</p>
												</div>
											</div>
										</div>
                                        <div class="securitySMS">
                                            <div class="formElements">
                                                <div class="formLabel">
                                                    <h3>Security (SMS) detail</h3> 
												</div>
                                                <div class="formFields">
													<a class="btn-blue" href="javascript:void(0);" name="send_verification_code" id="send_verification_code">Send verification code</a>
													<input type="hidden" id="verification_code_val" value="">
													<input name="employer_sms" id="employer_sms" type="text" class="input" placeholder=""> 
													<p class="helperText"> Verification code will be sent to your mobile number, make sure you've enetered the correct mobile number.</p>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="teamPermissions">
                                <h3>Team and Permissions </h3>
                                <p>Team member Information </p>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
											<div class="col-md-4">
                                                <input name="team_first_name" id="team_first_name" type="text" class="input" placeholder="First Name" value="<?=(isset($team_mem_info['first_name']) && !empty($team_mem_info['first_name'])) ? $team_mem_info['first_name'] :''?>"> </div>
                                            <div class="col-md-4">
                                                <input name="team_last_name" id="team_last_name" type="text" class="input" placeholder="Last name" value="<?=(isset($team_mem_info['last_name']) && !empty($team_mem_info['last_name'])) ? $team_mem_info['last_name'] :'';?>"> </div>
                                            <div class="col-md-4">
                                                <input name="team_title" id="team_title" type="text" class="input" placeholder="Title" value="<?=(isset($company_team_dtls['team_mem_title']) && !empty($company_team_dtls['team_mem_title'])) ? $company_team_dtls['team_mem_title'] :''?>"> </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
												<?php
													if(isset($team_mem_info['email']) && !empty($team_mem_info['email']))
													{
														$text="edit";
														$disabled="disabled";
														$val=$team_mem_info['email'];
													}
													else
													{
														$text="add";
														$disabled="";
														$val="";
													}
												?>
                                                <input name="team_email" id="team_email" type="text" class="input team_email_<?=$text;?>" <?=$disabled;?> placeholder="Email" value="<?=$val;?>"> </div>
                                            <div class="col-md-4">
                                                <input name="team_mobile" id="team_mobile" type="text" class="input" placeholder="Mobile Phone" value="<?=(isset($company_team_dtls['team_mem_mobile']) && !empty($company_team_dtls['team_mem_mobile'])) ? $company_team_dtls['team_mem_mobile'] :''?>"> </div>
                                            <div class="col-md-4">
                                                <input name="team_landline" id="team_landline" type="text" class="input" placeholder="Landline" value="<?=(isset($company_team_dtls['team_mem_landline']) && !empty($company_team_dtls['team_mem_landline'])) ? $company_team_dtls['team_mem_landline'] :''?>"> </div>
                                        </div>
                                    </div>
                                </div>
                                <p>Permissions </p>
                                <div class="row">
                                    <div class="form-group col-sm-6 permissions-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label> Admin </label>
                                                    </div>
                                                    <div class="col-md-8">
														<?php $ad_per=(isset($company_team_dtls['admin_permission']) && !empty($company_team_dtls['admin_permission'])) ? $company_team_dtls['admin_permission'] : '';?>
                                                        <select name="tmem_admin_permission" id="tmem_admin_permission" class="input team">
                                                            <option value="">Select</option>
                                                            <option value="full"  <?php echo ($ad_per== "full")?'selected':''; ?>>Full</option>
                                                            <option value="manage_finance" <?php echo ($ad_per== "manage_finance")?'selected':''; ?>>Manage Finance Only</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label> Hiring </label>
                                                    </div>
                                                    <div class="col-md-8">
													<?php $hiring_per=(isset($company_team_dtls['hiring_permission']) && !empty($company_team_dtls['hiring_permission'])) ? $company_team_dtls['hiring_permission'] : '';?>
                                                        <select name="tmem_hiring_permission" id="tmem_hiring_permission" class="input team">
                                                            <option value="">Select</option>
                                                            <option value="full"  <?php echo ($hiring_per== "full")?'selected':''; ?>>Full</option>
                                                            <option value="source_contractor"  <?php echo ($hiring_per== "source_contractor")?'selected':''; ?>>Source Contractors Only</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label> Training </label>
                                                    </div>
                                                    <div class="col-md-8">
														<?php $training_per=(isset($company_team_dtls['training_permission']) && !empty($company_team_dtls['training_permission'])) ? $company_team_dtls['training_permission'] : '';?>
                                                        <select name="tmem_trainig_permission" id="tmem_trainig_permission" class="input team">
                                                            <option value="">Select</option>
                                                            <option value="view_only" <?php echo ($training_per== "view_only")?'selected':''; ?>>View Only</option>
                                                            <option value="edit" <?php echo ($training_per== "edit")?'selected':''; ?>>Edit</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label> Activities </label>
                                                    </div>
                                                    <div class="col-md-8">
														<?php $act_per=(isset($company_team_dtls['activities_permission']) && !empty($company_team_dtls['activities_permission'])) ? $company_team_dtls['activities_permission'] : '';?>
                                                        <select name="tmem_activities_permission" id="tmem_activities_permission" class="input team">
                                                            <option value="">Select</option>
															<option value="view_only" <?php echo ($act_per== "view_only")?'selected':''; ?>>View Only</option>
                                                            <option value="edit" <?php echo ($act_per== "edit")?'selected':''; ?>>Edit</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label> Feedback </label>
                                                    </div>
                                                    <div class="col-md-8">
														<?php $feedback_per=(isset($company_team_dtls['feedback_permission']) && !empty($company_team_dtls['feedback_permission'])) ? $company_team_dtls['feedback_permission'] : '';?>
                                                        <select name="tmem_feedback_permission" id="tmem_feedback_permission" class="input team">
                                                            <option value="">Select</option>
                                                            <option value="view_only" <?php echo ($feedback_per== "view_only")?'selected':''; ?>>View Only</option>
                                                            <option value="edit" <?php echo ($feedback_per== "edit")?'selected':''; ?>>Edit</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label> Can message </label>
                                                    </div>
                                                    <div class="col-md-8">
														<?php $msg_per=(isset($company_team_dtls['message_permission']) && !empty($company_team_dtls['message_permission'])) ? $company_team_dtls['message_permission'] : '';?>
                                                        <select name="tmem_message_permission" id="tmem_message_permission" class="input team">
                                                            <option value="">Select</option>
                                                            <option value="nobody" <?php echo ($msg_per== "nobody")?'selected':''; ?>>Nobody</option>
                                                            <option value="everybody" <?php echo ($msg_per== "everybody")?'selected':''; ?>>Everybody</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="notiSettings">
                                <h3>Notification Settings</h3>
                                <div class="row">
                                    <div class="form-group col-sm-4 notification-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label> Desktop notifications </label>
                                            </div>
                                            <div class="col-md-6">
												<?php $desktop=(isset($company_notifi['desktop']) && !empty($company_notifi['desktop'])) ? $company_notifi['desktop'] : '';?>
                                                <select name="company_desktop_notifi" id="company_desktop_notifi" class="input">
                                                    <option value="">Select</option>
													<option value="all" <?php echo ($desktop == "all") ?'selected':''; ?>>All Activity	</option>
													<option value="messages" <?php echo ($desktop == "messages") ?'selected':''; ?>>Messages</option>
													<option value="none" <?php echo ($desktop == "none") ?'selected':''; ?>>None</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Mobile notifications </label>
                                            </div>
                                            <div class="col-md-6">
												<?php $mobile=(isset($company_notifi['mobile']) && !empty($company_notifi['mobile'])) ? $company_notifi['mobile'] : '';?>
                                                <select name="company_mobile_notifi" id="company_mobile_notifi" class="input">
                                                    <option value="">Select</option>
													<option value="all" <?php echo ($mobile == "all") ?'selected':''; ?>>All Activity	</option>
													<option value="messages" <?php echo ($mobile == "messages") ?'selected':''; ?>>Messages</option>
													<option value="none" <?php echo ($mobile == "none") ?'selected':''; ?>>None</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Email notifications </label>
                                            </div>
                                            <div class="col-md-6">
												<?php $email=(isset($company_notifi['email']) && !empty($company_notifi['email'])) ? $company_notifi['email'] : '';?>
                                                <select name="company_email_notifi" id="company_email_notifi" class="input">
                                                    <option value="">Select</option>
													<option value="all" <?php echo ($email == "all") ?'selected':''; ?>>All Activity	</option>
													<option value="messages" <?php echo ($email == "messages") ?'selected':''; ?>>Messages</option>
													<option value="none" <?php echo ($email == "none") ?'selected':''; ?>>None</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p>Other Email Update </p>
                                <ul class="other-email-update-list">
                                    <li>
                                        <label for="jobIsPosted" class="custom-checkbox">
                                            <input id="jobIsPosted" name="jobIsPosted" <?php echo (isset($company_notifi['job_posted']) && !empty($company_notifi['job_posted']) && $company_notifi['job_posted'] == "on") ? 'checked' : '';?> type="checkbox"> <span class="custom-check"></span> A job is posted or modified</label>
                                    </li>
                                    <li>
                                        <label for="offerIsAccepted" class="custom-checkbox">
                                            <input id="offerIsAccepted" name="offerIsAccepted" type="checkbox" <?php echo (isset($company_notifi['offer_accepted']) && !empty($company_notifi['offer_accepted']) && $company_notifi['offer_accepted'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> An offer is accepted</label>
                                    </li>
                                     <li>
                                        <label for="offerIsModified" class="custom-checkbox">
                                            <input id="offerIsModified" name="offerIsModified" type="checkbox" <?php echo (isset($company_notifi['offer_changes_del']) && !empty($company_notifi['offer_changes_del']) && $company_notifi['offer_changes_del'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> An offer is modified, declined or withdrawn</label>
                                    </li>
                                     <li>
                                        <label for="jobExpire" class="custom-checkbox">
                                            <input id="jobExpire" name="jobExpire" type="checkbox" <?php echo (isset($company_notifi['job_post_expire']) && !empty($company_notifi['job_post_expire']) && $company_notifi['job_post_expire'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> A job posting will expire</label>
                                    </li>
                                    <li>
                                        <label for="contractBegins" class="custom-checkbox">
                                            <input id="contractBegins" name="contractBegins" type="checkbox" <?php echo (isset($company_notifi['contract_begin']) && !empty($company_notifi['contract_begin']) && $company_notifi['contract_begin'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> A hire is made or a contract begins</label>
                                    </li>
                                    <li>
                                        <label for="contractModified" class="custom-checkbox">
                                            <input id="contractModified" name="contractModified" type="checkbox" <?php echo (isset($company_notifi['contract_modified']) && !empty($company_notifi['contract_modified']) && $company_notifi['contract_modified'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> Contract terms are modified</label>
                                    </li>
                                    
                                     <li>
                                        <label for="activityStarting" class="custom-checkbox">
                                            <input id="activityStarting" name="activityStarting" type="checkbox" <?php echo (isset($company_notifi['activity_start']) && !empty($company_notifi['activity_start']) && $company_notifi['activity_start'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> An activity is starting</label>
                                    </li>
                                    
                                    <li>
                                        <label for="activityCompleted" class="custom-checkbox">
                                            <input id="activityCompleted" name="activityCompleted" type="checkbox" <?php echo (isset($company_notifi['activity_complete']) && !empty($company_notifi['activity_complete']) && $company_notifi['activity_complete'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> An activity is completed</label>
                                    </li>
                                     <li>
                                        <label for="expenseReport" class="custom-checkbox">
                                            <input id="expenseReport" name="expenseReport" type="checkbox" <?php echo (isset($company_notifi['job_report_submitted']) && !empty($company_notifi['job_report_submitted']) && $company_notifi['job_report_submitted'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> A job and expense report has been submitted</label>
                                    </li>
                                    
                                     <li>
                                        <label for="trainingCompleted" class="custom-checkbox">
                                            <input id="trainingCompleted" name="trainingCompleted" type="checkbox" <?php echo (isset($company_notifi['training_completed']) && !empty($company_notifi['training_completed']) && $company_notifi['training_completed'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> Training has been completed</label>
                                    </li>
                                    
                                     <li>
                                        <label for="trainingNotCompleted" class="custom-checkbox">
                                            <input id="trainingNotCompleted" name="trainingNotCompleted" type="checkbox" <?php echo (isset($company_notifi['training_not_completed']) && !empty($company_notifi['training_not_completed']) && $company_notifi['training_not_completed'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> Training not completed (24 hours before scheduled activity)</label>
                                    </li>
                                    
                                      <li>
                                        <label for="contractEnd" class="custom-checkbox">
                                            <input id="contractEnd" name="contractEnd" type="checkbox" <?php echo (isset($company_notifi['contract_ended']) && !empty($company_notifi['contract_ended']) && $company_notifi['contract_ended'] == "on") ? 'checked' : '';?>> <span class="custom-check"></span> A contract has ended</label>
                                    </li>
                                    
                                    
                                    
                                    
                                </ul>
                            </div>
                            
                            <div class="text-right">
                                <input type="submit" class="company-profile-btn" name="save_company_details" value="Save">
                            
                            </div>
                        </form>
                    </aside>
                </div>
            </div>
        </section>
    </main>
    