
<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <div class="profile-details saved">
            <div class="add-avatar">
				<?php if(isset($company_info['company_image']) && !empty($company_info['company_image'])) 
				{	
					$imgsrc=BASE_URL."/static/images/employer/".$company_info['company_image'];
				}
				else
				{
					$imgsrc=BASE_URL."/static/images/company-profile-pic.jpg";
				}
				?>
              <div class="avatar-set" style="background-image:url('<?php echo $imgsrc;?>');"></div>
            </div>
            <div class="add-personal-details">
              <h2 class="pro-title"><?php if(isset($company_data['company_name']) && !empty($company_data['company_name'])) echo $company_data['company_name']; ?></h2>
              <p class="pro-location"><?php if(isset($city) && !empty($city)) echo $city; ?><?php if(isset($country) && !empty($country)) echo ', '. $country; ?></p>
              <p class="pro-industries">
				<?php if(isset($company_info['company_indus']) && !empty($company_info['company_indus'])) 
				{
					$all_indus=explode(',',$company_info['company_indus']);
				}
				if(!empty($all_indus))
				{
					foreach($all_indus as $ind)
					{
					?>
					  <span class="industry-tag"><?php echo $ind; ?></span>
					<?php
					}
				}
				  ?>
			  </p>
            </div>
          </div>
          <div class="more-details">
            <article class="ff-description">
              <h3>Business Description</h3>
			  
			   <?php 
					$description=$company_info['company_busi_desc'];
					$desc=explode(PHP_EOL,$description);
					 $length=strlen($description);
				  ?>
				  <p><?php  echo $desc[0];?></p>
				  <?php if($length >= 500){ ?>
				  <div align="right">
					<div class="more-content" style="display:none;">
					 <?php for($i=1;$i<count($desc); $i++){ ?>
						 <p><?=$desc[$i];?></p>
					 <?php } ?>
					</div>
					<a id="moreToggle" href="javascript:void(0);" class="view-more-toggle"><span class="sr-only">View More</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
				 </div>
				  <?php } ?>
			
            </article>
            <div class="company-social-links">
              <h2>Company Social Links:
				 <?php if(isset($company_info['company_website']) && !empty($company_info['company_website'])) { ?>
					<a href="<?php echo $company_info['company_website'];?>" target="_blank" class="web-link"><span class="sr-only">Website</span></a>
				 <?php }?>
				
				<?php if(isset($company_info['company_fb']) && !empty($company_info['company_fb'])) { ?>
					 <a href="<?php echo $company_info['company_fb'];?>" class="fb-link" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
				 <?php }?>
				 
				 <?php if(isset($company_info['company_linkedin']) && !empty($company_info['company_linkedin'])) { ?>
					<a href="<?php echo $company_info['company_linkedin'];?>" class="linkedin-link"target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				 <?php }?>
				 
				 <?php if(isset($company_data['email']) && !empty($company_data['email'])) { ?>
					<a href="mailto:<?php echo $company_data['email'];?>" class="email-link"><span class="sr-only">Email Address</span></a>
				 <?php }?>
				</h2>
            </div>
			 <?php  
				if(isset($company_info['company_about_us']) && !empty($description=$company_info['company_about_us']))
				{?>
					<article class="ff-description">
					  <h3>About the Company</h3>
					  <?php
					  $descr=explode(PHP_EOL,$company_info['company_about_us']); 
					  foreach($descr as $d)
					  {
					  ?>  
						<p><?php echo $company_info['company_about_us'];?></p>
				<?php } ?>
					</article>
		 <?php  } ?>
            <div class="pro-status-info">
              <ul>
                <li>
                  <h4>Review</h4>
                  <p><img src="<?php echo BASE_URL ;?>/static/images/5-star-rating.png" alt="rate"> <span class="reviews-number">2</span> reviews</p>
                </li>
                <li>
					<?php 
						if(isset($posted_jobs) && !empty($posted_jobs) && $posted_jobs > 0)
						{
							if($posted_jobs > 1)
							{
								$content=$posted_jobs." Jobs Posted";
							}
							else 
							{
								$content=$posted_jobs." Job Posted";
							}
						} 
						else
						{
							$content="No Job Posted";
						}
					?>
						<h4><?php echo $content; ?></h4>
						<?php 
						if(isset($open_jobs) && !empty($open_jobs) && $open_jobs > 0)
						{
							if($open_jobs > 1)
							{
								$openjobs=', '.$open_jobs." Open Jobs";
							}
							else 
							{
								$openjobs=', '.$open_jobs." Open Job";
							}
						}
						else
						{
							$openjobs="";
						}
						?>
						<p>80% Hire Rate<?php echo $openjobs;?></p>
                </li>
                <li>
                  <h4>$1.74/hr Avg Hourly Rate Paid</h4>
                  <p>595 Hours</p>
                </li>
                <li>
                  <h4>Over $30,000 Total Spent</h4>
				  <?php 
				  if(isset($employer_hires) && !empty($employer_hires) && $employer_hires > 0) 
				  {
					 $hires=($employer_hires > 1)?$employer_hires.' Hires':$employer_hires.' Hire';
				  }
				  else
				  {
					  $hires="No Hires";
				  }
				  ?>
				  
                  <p><?php echo $hires; ?><?php if(isset($employer_active_hires) && !empty($employer_active_hires)) { echo ','. $employer_active_hires .' Active'; } ?></p>
                  <p>Member Since <?php if(isset($joining_date) && !empty($joining_date)) echo $joining_date;?> </p>
                </li>
              </ul>
            </div>
            <div class="training-area">
              <h3>Training (Public)</h3>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <th scope="col">Course Names</th>
                  <th scope="col">Description</th>
                </tr>
                <tr>
                  <td>Selling BBQs</td>
                  <td>Course 1708 on basic selling</td>
                </tr>
                <tr>
                  <td>Selling BBQs</td>
                  <td>Course 1708 on basic selling</td>
                </tr>
              </table>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>
