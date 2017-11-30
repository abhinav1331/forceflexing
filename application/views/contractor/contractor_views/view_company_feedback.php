<main role="main">
  <section class="feedbacks-wrap">
    <div class="container">
		<?php if(!empty($data)){ ?>
		<div class="contractors-feedback">
			<h2>Feedback for <?php  if(!empty($data['job_title'])) echo $data['job_title']; ?></h2>
			<p>Share your experience! Your honest feedback provides helpful information to both the Company and the ForceFlexing community.</p>
			<form name="" action="" id="feedback_form" method="post">
				<div class="private-feedback">
				  <h3>Private Feedback</h3>
				  <p>This feedback will be shared with the company but not shared publicly. <a href="#">Learn more</a></p>
				  <p>
					<label>Reason contract ended</label>
					<select name="reason_contract_ended">
					  <option value="">Select Reason</option>
					  <option value="job_completed">Job completed successfully</option>
					  <option value="job_not_required">Job no longer required</option>
					  <option value="other">Other</option>
					</select>
				  </p>
				   <p>
						<label class="block">How likely are you to recommend this Company to a friend or a colleague?</label>
						<label class="inline"><strong>Not at all likely:</strong></label>
						<label class="custom-radio">
							<input type="radio" name="recommendation_score" value="0" id="RadioGroup1_0" checked>
							<span class="radio-btn"></span>0
						</label>
						<label class="custom-radio">
							<input type="radio" name="recommendation_score" value="1" id="RadioGroup1_1">
							<span class="radio-btn"></span>1
						</label>
						<label class="custom-radio">
							<input type="radio" name="recommendation_score" value="2" id="RadioGroup1_2">
							<span class="radio-btn"></span>2
						</label>
						<label class="custom-radio">
							<input type="radio" name="recommendation_score" value="3" id="RadioGroup1_3">
							<span class="radio-btn"></span>3
						 </label>
						<label class="custom-radio">
							<input type="radio" name="recommendation_score" value="4" id="RadioGroup1_4">
							<span class="radio-btn"></span>4
						</label>
						<label class="custom-radio">
							<input type="radio" name="recommendation_score" value="5" id="RadioGroup1_5">
							<span class="radio-btn"></span>5
						</label>
						<label class="custom-radio">
							<input type="radio" name="recommendation_score" value="6" id="RadioGroup1_6">
							<span class="radio-btn"></span>6
						</label>
						<label class="custom-radio">
							<input type="radio" name="recommendation_score" value="7" id="RadioGroup1_7">
							<span class="radio-btn"></span>7
						</label>
						<label class="inline"><strong>:Extremely likely</strong></label>
				  </p>
				</div>
				<div class="public-feedback">
					<h3>Public Feedback</h3>
					<p>This feedback will be shared on the contractor’s profile. <a href="#">Learn more</a></p>
					<p>
						<label>Feedback to Contractor</label>
					</p>
					<div class="feedback-table">
						<p><span class="fdbck-title">Organization/Preparedness</span>
							<label class="custom-radio">
								<input type="radio" name="orga_prepa" value="2" id="RadioGroup2_0" checked>
								<span class="radio-btn"></span>Inadequate
							</label>
							<label class="custom-radio">
								<input type="radio" name="orga_prepa" value="4" id="RadioGroup2_1">
								<span class="radio-btn"></span>Weak
							</label>
							<label class="custom-radio">
								<input type="radio" name="orga_prepa" value="6" id="RadioGroup2_2">
								<span class="radio-btn"></span>Good
							</label>
							<label class="custom-radio">
								<input type="radio" name="orga_prepa" value="8" id="RadioGroup2_3">
								<span class="radio-btn"></span>Excellent
							</label>
							<label class="custom-radio">
								<input type="radio" name="orga_prepa" value="10" id="RadioGroup2_4">
								<span class="radio-btn"></span>Exceptional
							</label>
						</p>
						<p>
							<span class="fdbck-title">Training</span>
							<label class="custom-radio">
								<input type="radio" name="training" value="2" id="RadioGroup3_0" checked>
								<span class="radio-btn"></span>Inadequate
							</label>
							<label class="custom-radio">
								<input type="radio" name="training" value="4" id="RadioGroup3_1">
								<span class="radio-btn"></span>Weak
							</label>
							<label class="custom-radio">
								<input type="radio" name="training" value="6" id="RadioGroup3_2">
								<span class="radio-btn"></span>Good
							</label>
							<label class="custom-radio">
								<input type="radio" name="training" value="8" id="RadioGroup3_3">
								<span class="radio-btn"></span>Excellent
							</label>
							<label class="custom-radio">
								<input type="radio" name="training" value="10" id="RadioGroup3_4">
								<span class="radio-btn"></span>Exceptional
							</label>
						</p>
						<p>
							<span class="fdbck-title">Communication</span>
							<label class="custom-radio">
								<input type="radio" name="communication" value="2" id="RadioGroup4_0" checked>
								<span class="radio-btn"></span>Inadequate
							</label>
							<label class="custom-radio">
								<input type="radio" name="communication" value="4" id="RadioGroup4_1">
								<span class="radio-btn"></span>Weak
							</label>
							<label class="custom-radio">
								<input type="radio" name="communication" value="6" id="RadioGroup4_2">
								<span class="radio-btn"></span>Good
							</label>
							<label class="custom-radio">
								<input type="radio" name="communication" value="8" id="RadioGroup4_3">
								<span class="radio-btn"></span>Excellent
							</label>
							<label class="custom-radio">
								<input type="radio" name="communication" value="10" id="RadioGroup4_4">
								<span class="radio-btn"></span>Exceptional
							</label>
						</p>
						<p>
							<span class="fdbck-title">Adherence to Job Description</span>
							<label class="custom-radio">
								<input type="radio" name="ajd" value="2" id="RadioGroup5_0" checked>
								<span class="radio-btn"></span>Inadequate
							</label>
							<label class="custom-radio">
								<input type="radio" name="ajd" value="4" id="RadioGroup5_1">
								<span class="radio-btn"></span>Weak
							</label>
							<label class="custom-radio">
								<input type="radio" name="ajd" value="6" id="RadioGroup5_2">
								<span class="radio-btn"></span>Good
							</label>
							<label class="custom-radio">
								<input type="radio" name="ajd" value="8" id="RadioGroup5_3">
								<span class="radio-btn"></span>Excellent
							</label>
							<label class="custom-radio">
								<input type="radio" name="ajd" value="10" id="RadioGroup5_4">
								<span class="radio-btn"></span>Exceptional
							</label>
						</p>
						<p>
							<span class="fdbck-title">Adherence to Job Contract</span>
							<label class="custom-radio">
								<input type="radio" name="ajc" value="2" id="RadioGroup6_0" checked>
								<span class="radio-btn"></span>Inadequate
							</label>
							<label class="custom-radio">
								<input type="radio" name="ajc" value="4" id="RadioGroup6_1">
								<span class="radio-btn"></span>Weak
							</label>
							<label class="custom-radio">
								<input type="radio" name="ajc" value="6" id="RadioGroup6_2">
								<span class="radio-btn"></span>Good
							</label>
							<label class="custom-radio">
								<input type="radio" name="ajc" value="8" id="RadioGroup6_3">
								<span class="radio-btn"></span>Excellent
							</label>
							<label class="custom-radio">
								<input type="radio" name="ajc" value="10" id="RadioGroup6_4">
								<span class="radio-btn"></span>Exceptional
							</label>
						</p>
					</div>
					<h4 id="total">Total Score: 2</h4>
					<input type="hidden" name="average_score" id="average_score" value="2">
					<div class="share-feedback">
						<h5>Share your experience with this Company</h5>
						<textarea name="experience" cols="" rows=""></textarea>
					 </div>
				</div>
				<div class="fdbk-btns">
					<input type="submit" name="submit_feedback" class="btn btn-blue" value="Submit Feedback">
					<input type="button" name="cancel_feedback" class="btn btn-blue" value="Cancel">
				</div>
			</form>	
		</div>
		<?php } 
		else
		{?>
			<div class="contractors-feedback">
				<h2>Sorry No such Contract Exists!!</h2>
			</div>
<?php  }
		?>
	</div>
  </section>
</main>