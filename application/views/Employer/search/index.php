
<?php $url = substr_replace(SITEURL, "", -1).''.$_SERVER['ORIG_PATH_INFO']; echo "<script> var searchurl; searchurl='$url'; </script>"; ?>
<main role="main">
  <section class="page-wrap">
    <div class="container">
      <div class="page-main">
        <section class="search-contractors-wrap">
          <h1>Search Contractors</h1>
          <div class="contractor-search-area">
            <form>
              <select name="" class="input inline">
                <option>Type of Job</option>
                <option>Category</option>
                <option>Category</option>
              </select>
              <input name=""  type="submit" value="Search" class="btn submit">
            </form>
          </div>
          <div class="contractor-filters-area">
          <h3>Filters</h3>		 
		  <form id="searchContract" method="get">	  
            <div class="row">
              <div class="col-sm-6">
                <p>
                  <label>Hourly Rate:</label>
                  <span class="tableCell">$<span id="ff-slider-value-min">10.00</span> to $<span id="ff-slider-value-max">200.00</span> Per Hour <span id="ff-range-slider1" class="hourlyRange"></span>
				  <input type="hidden" name="hourly_wages" value="" />
					</span> 
				</p>
                <p>
                  <label>Availability:</label>
                  <select name="availability" class="input">
                    <option value="">Select Availability</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>                    
                  </select>
                </p>
                <p>
                  <label>Hours Billed:</label>
                  <select name="hours_worked" class="input">
                   <option value="">Select Hours Billed</option>
                    <option value="100">Below 100 Hours</option>
                    <option value="100-500">Between 100 - 500 Hours</option>
                    <option value="500+">500+ Hours</option>
                  </select>
                </p>
                <p>
                  <label>Freelancer Type:</label>
                  <select name="free_type" class="input">
                    <option value="">Select Freelancer Type</option>
                    <option value="freelancer">Freelancer</option>
                    <option value="agency">Agency</option>                    
                  </select>
                </p>
                <p>
                  <label>Industry Expertise:</label>
				  <input type="text"  name="industries" class="input industries" Placeholder="Select Industry">          
                </p>
              </div>
              <div class="col-sm-6">
               <p>
                  <label>Job Success:</label>
                  <span class="tableCell">
                  <span class="rangeValue"><span id="hourlyRateRangeValue"></span>%</span> <span class="slider" id="hourlyRateRangeSlider"> <span id="trackFilled"></span> </span>				  				  
                  </span>
				  <input type="hidden" name="job_success" value="" />
				  </p>
                <p>
                <p>
                  <label>Location:</label>
                  <select name="location" class="input">
                   <?php echo $locations; ?>
                  </select>
                </p>
                <p>
                  <label>Last Active:</label>
                  <select name="last_login_time" class="input">
                    <option value="">Select Last Active</option>
                    <option value="1 WEEK">Past 1 Week</option>
                    <option value="3 MONTH">Past 1 - 3 Months</option>
                    <option value="6 MONTH">Past 3 - 6 Months</option>
                    <option value="1 YEAR">Past 1 Year</option>                    
                  </select>
                </p>
                <p>
                  <label>Language:</label>
                  <input type="text"  name="languages" id="jp_language" class="input" Placeholder="Select language">
                </p>
                <p>
                  <label>Training Completed:</label>
                  <select name="" class="input">
                    <option>Option 1</option>
                    <option>Option 2</option>
                    <option>Option 3</option>
                  </select>
                </p>
              </div>
            </div>
          </div>
          <div class="clear-filter-btns">
          <button type="button" class="btn btn-blue">Clear Filters</button>
          <button type="submit" class="btn btn-gray">Search</button>
          </div>
          </form>
          <div id="results" class="contractors-list">
          <article class="contractor-box">
          <figure class="contractor-avatar">
            <img src="../images/contracts-user-img.jpg" alt="Contractor Picture"> </figure>
            <div class="contractor-action">
            <div class="dropdown">
                    <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                    <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                      <li><a href="#">Invite</a></li>
                      <li><a href="#">Save</a></li>
                    </ul>
                  </div>
            </div>
            
            <div class="contractor-pro-details"><h3><a href="#">Lorem ipsum</a></h3>
            <p>Lorem ipsum/ Dummy  heading</p>
            <p><strong>Hourly wage:</strong> $22/hour <span class="sep">I</span> <strong>Hours worked:</strong> 245 hours <span class="sep">I</span> <strong>Job Success:</strong> 100%</p></div>
            <div class="contractror-descrip">
            <h3>Description:</h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p> 
            </div>
            <div class="contractor-training">
            <h3>Training completed:</h3>
            <p class="pro-industries"><span class="industry-tag">Health care industrie</span> <span class="industry-tag">Mechanical industrie</span> <span class="industry-tag">Automobile industrie</span></p>
            </div>
            <div class="contractor-experience">
            <p><strong>Industry Expertise:</strong> 10 years</p> 
            </div>
          </article>
          <article class="contractor-box">
          <figure class="contractor-avatar">
            <img src="../images/contracts-user-img.jpg" alt="Contractor Picture"> </figure>
            <div class="contractor-action">
            <div class="dropdown">
                    <button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
                    <ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
                      <li><a href="#">Invite</a></li>
                      <li><a href="#">Save</a></li>
                    </ul>
                  </div>
            </div>
            
            <div class="contractor-pro-details"><h3><a href="#">Lorem ipsum</a></h3>
            <p>Lorem ipsum/ Dummy  heading</p>
            <p><strong>Hourly wage:</strong> $22/hour <span class="sep">I</span> <strong>Hours worked:</strong> 245 hours <span class="sep">I</span> <strong>Job Success:</strong> 100%</p></div>
            <div class="contractror-descrip">
            <h3>Description:</h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p> 
            </div>
            <div class="contractor-training">
            <h3>Training completed:</h3>
            <p class="pro-industries"><span class="industry-tag">Health care industrie</span> <span class="industry-tag">Mechanical industrie</span> <span class="industry-tag">Automobile industrie</span></p>
            </div>
            <div class="contractor-experience">
            <p><strong>Industry Expertise:</strong> 10 years</p> 
            </div>
          </article>
          <div class="clr"></div>
          <div class="contractor-pagination">
          <a href="#" class="prev-btn">Previous</a> <a href="#" class="current">1</a> <a href="#">2</a> <a href="#">3</a> <a href="#">4</a> <a href="#" class="next-btn">Next</a>
          </div>
          </div>
        </section>
		<div class="contractor-pagination">
			
		</div>
      </div>
    </div>
  </section>
</main>