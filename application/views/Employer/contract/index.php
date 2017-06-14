<?php 
/*  echo "<pre>";
    print_r($jobApplication);
  echo "</pre>";*/
 ?>
<?php 
  if(isset($_POST['submit'])) {


    if(isset($_FILES['fileUpload']['name'])) {
      $extTmp = explode("." , $_FILES['fileUpload']['name']);
      $fileName = time();
      $target_dir = ABSPATH."/static/uploads/";
      $target_file = $target_dir . $fileName.".".$extTmp[1];
      move_uploaded_file($_FILES['fileUpload']['tmp_name'], $target_file);
      $fileUrl = BASE_URL."/static/uploads/".$fileName.".".$extTmp[1];
      $data =  array(
       'url'=>$fileUrl, 
       'attachment_location'=>"JobPost", 
       'attachment_author'=>$_POST['current_user_id'],
       'created_date'=>$fileName,
       'modified_date'=>$fileName
      );
      $Results = $Model->Insert_users($data,PREFIX.'attachments');
      if(isset($_POST['already-attached'])) {
        $Results = $_POST['already-attached'];
      } else {
        echo $Results = $Results;
      }
    } else {
      $Results = "";
    }
    echo json_encode($_POST['activityId']);
    echo json_encode($_POST['extra']);

    $data=array(
      'job_id'=>$_POST['job_id'],
      'activity_id'=>json_encode($_POST['activityId']),
      'contractor_id'=>$_POST['user_id'],
      'external_expanditure'=>json_encode($_POST['extra']),
      'flex_amount'=>$_POST['priceFlex'],
      'additionalInfo'=>$_POST['additionalInformation'],
      'attachmentId'=>$Results,
      'status'=>0
      );
    $applied_job_id=$Model->Insert_users($data,PREFIX.'hire_contractor'); 

?>


<script>
  jQuery(document).ready(function(){
  setTimeout(function(){
            toastr.success("Hiring Request has been sent successfuly");
            window.Location.href = "http://force.imarkclients.com/employer/job_report";
 }, 3000);
});

</script>
<?php
  }

?>

<?php
 ?>
<main role="main">
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" class="job_id" name="job_id" value="<?php echo $job_id; ?>">
    <input type="hidden" class="user_id" name="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" class="user_id" name="current_user_id" value="<?php echo $current_user_data['id']; ?>">
  <section class="page-wrap contract">
    <div class="container">
      <div class="page-main">
        <aside class="main-page-body">
          <h2>Web Development</h2>
          <h4 class="nameLabel">William Bach</h4>
          <div class="more-details">
            <div class="activitiesAssociated">
              <h3>Activities Associated with Job</h3>
              <div class="job-activities-table">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody class="mrTbody">
                    <tr>
                      <th scope="col">Activity Name</th>
                      <th scope="col">Activity Time</th>
                      <th scope="col">Location</th>
                      <th scope="col">Contact Name </th>
                      <th scope="col">Close Job</th>
                      <th class="noBorder" scope="col">&nbsp;</th>
                    </tr>
                    <?php 
                    $i = 0;
                      foreach ($jobApplication as $key => $value) {
                       $contractor_id = $value['activity_id'];
                        $emplyerdetails = $Model->Get_column1('*','id',$contractor_id,PREFIX.'job_activities');
                      
                     ?>
                    <tr>
                      <td><input name="activityId[]" type="hidden" class="activityId" value="<?php echo $emplyerdetails[0]['id']; ?>"> <input type="hidden" class="appliedId" value="<?php echo $value['id']; ?>"><?php echo $emplyerdetails[0]['activity_name']; ?></td>
                      <td><?php echo $emplyerdetails[0]['start_datetime']; ?></td>
                      <td><a href="#"><?php echo $emplyerdetails[0]['city']; ?></a></td>
                      <td><?php echo $emplyerdetails[0]['first_name']; ?> <?php echo $emplyerdetails[0]['last_name']; ?></td>
                      <td align="center"><label for="closeJob_<?php echo $i; ?>" class="custom-checkbox noLabel" >
                          <input type="hidden" class="thisJobIdActivity" value="<?php echo $emplyerdetails[0]['id']; ?>">
                          <input <?php if($emplyerdetails[0]['job_status'] ==1) { echo "checked"; } ?> id="closeJob_<?php echo  $i; ?>" type="checkbox" onclick="JobStatus(this);">
                          <span class="custom-check"></span></label></td>
                      <td class="noBorder"><div class="actionButtons"> <a href="javascript:void(0)" onclick="viewActivity(this);" class="btn btn-blue">View</a> <a href="javascript:void(0)" onclick="deleteActivity(this);" class="btn btn-gray">Delete </a> <a href="javascript:void(0)" onclick="editActivity(this);" class="btn btn-blue">Edit</a> </div></td>
                    </tr>
                    <?php 
                     $i++; }

                      ?>
                  </tbody>
                </table>
              </div>
              <a href="javascript:void(0)" class="btn btn-blue btn-large" onclick="addNewActivity();">Add Activities</a> </div>
            <div class="requiredTraining">
              <h3>Required Training and Courses</h3>
              <div class="job-activities-table">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <th scope="col">Course Names </th>
                      <th scope="col">Description</th>
                      <th scope="col">Due Date </th>
                      <th scope="col">Needed Score</th>
                      <th class="noBorder" scope="col">&nbsp;</th>
                    </tr>
                    <tr>
                      <td>Selling BBQs</td>
                      <td><a href="#">Course 1708 on basic selling</a></td>
                      <td>11/00/00</td>
                      <td align="center"><label for="closeJob_1" class="custom-checkbox noLabel">
                          <input id="closeJob_1" type="checkbox">
                          <span class="custom-check"></span></label></td>
                      <td class="noBorder"><div class="actionButtons"> <a href="#" class="btn btn-blue">View</a> <a href="#" class="btn btn-gray">Delete </a> <a href="#" class="btn btn-blue">Edit</a> </div></td>
                    </tr>
                    <tr>
                      <td>Selling BBQs</td>
                      <td><a href="#">Course 1708 on basic selling</a></td>
                      <td>11/00/00</td>
                      <td align="center"><label for="closeJob_1" class="custom-checkbox noLabel">
                          <input id="closeJob_1" type="checkbox">
                          <span class="custom-check"></span></label></td>
                      <td class="noBorder"><div class="actionButtons"> <a href="#" class="btn btn-blue">View</a> <a href="#" class="btn btn-gray">Delete </a> <a href="#" class="btn btn-blue">Edit</a> </div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <a href="#" class="btn btn-blue btn-large">Add or Edit Courses</a> </div>
            <div class="paymentInformation">
              <div class="clearfix">
                <h3 class="pull-left">Payment Information (will vary based on hourly or fixed)</h3>
                <a href="javascript:void(0)" class="btn btn-blue btn-small pull-right" data-toggle="modal" data-target="#MyModelActionOtherExpanditure">Edit</a></div>
              <div class="paymentOptions">
                <?php 
                  $includedExpenditure = array();
                  $includedExpenditureIndex = array();
                  $otherExpenditure = $Model->Get_column1('*','job_id',$value['job_id'],PREFIX.'job_expenditure');
                  foreach ($otherExpenditure as $key => $value) {
                   $includedExpenditure[] = $value['name'];
                   $includedExpenditureIndex = array('name' => $value['name'] , 'id' => $value['id']);
                   //echo '<input type="hidden" name="externalExpnditureArray[]" value="'. htmlspecialchars(serialize($includedExpenditureIndex)). '">';
                  }
                  
                  
                  
                 ?>
                <label for="cFood" class="custom-checkbox">
                  <input disabled id="cFood"  name="extra[]" type="checkbox" <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($Model ,"name" ,"food" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?> <?php if (in_array("food", $includedExpenditure)) { echo "checked"; } ?>>
                  <span class="custom-check"></span> Cover Food</label>
                <label for="cParking" class="custom-checkbox">
                  <input disabled id="cParking"  name="extra[]" type="checkbox" <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($Model ,"name" ,"parking" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?> <?php if (in_array("parking", $includedExpenditure)) { echo "checked"; } ?>>
                  <span class="custom-check"></span> Cover Parking</label>
                <label for="cTolls" class="custom-checkbox">
                  <input disabled id="cTolls"  name="extra[]" type="checkbox"  <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($Model ,"name" ,"tolls" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?>  <?php if (in_array("tolls", $includedExpenditure)) { echo "checked"; } ?>>
                  <span class="custom-check"></span> Cover Tolls</label>
                <label for="cTips" class="custom-checkbox">
                  <input disabled id="cTips"  name="extra[]" type="checkbox"   <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($Model ,"name" ,"tips" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?>  <?php if (in_array("tips", $includedExpenditure)) { echo "checked"; } ?>>
                  <span class="custom-check"></span> Cover Tips</label>
                <label for="cOther" class="custom-checkbox">
                  <input disabled id="cOther"  name="extra[]" type="checkbox"   <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($Model ,"name" ,"other" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?>  <?php if (in_array("other", $includedExpenditure)) { echo "checked"; } ?>>
                  <span class="custom-check"></span> Cover Other Expenses</label>
              
              </div>
            </div>
            
            <div class="paymentTypes">
            <h3>Hourly/Fixed price</h3>
            
            <div class="flexRateInfo">
            <h3>Current Flex amount</h3>
            <input type="hidden" class="priceFlex" name="priceFlex" value="<?php echo $total_price; ?>">
            <?php 
              if($job_type == "hourly") {
                ?>
                <div class="currentFlexAmout">$<?php echo $total_price; ?>/hour</div>
                <?php
              } else {
                ?>
                <div class="currentFlexAmout">$<?php echo $total_price; ?></div>
                <?php
              }
             ?>
            <p class="allowedHoursOverage">Hour overage allowed per activity </p>
            <select name="" class="input small">
                                <option>Lorem ipsum</option>
                                <option>Lorem ipsum</option>
                              </select>
            </div>
            </div>
            
            
            <div class="additionalInformation">
            <h3>Additional Information</h3>
            
            <textarea cols="" rows="" class="input" placeholder="" name="additionalInformation"></textarea>
            </div>
            
            <div class="attach-file">
              <input class="input-file" id="my-file" type="file" name="fileUpload">
              <label tabindex="0" for="my-file" class="input-file-trigger"><i class="attachment-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="214 138 181 375" xml:space="preserve">
                <path d="M263.173,227.048v196.001c0,22.934,18.656,41.59,41.596,41.59c22.939,0,41.589-18.649,41.589-41.59V200.665h-0.188
	c-1.971-34.938-30.942-62.787-66.378-62.787c-35.429,0-64.388,27.849-66.358,62.787h-0.188v218.619
	c0,51.441,40.607,93.3,90.536,93.3c49.921,0,90.529-41.858,90.529-93.3V193.125h-22.866v226.158c0,38.831-30.357,70.44-67.663,70.44
	c-37.312,0-67.677-31.603-67.677-70.44V204.438c0-24.09,19.604-43.688,43.688-43.688c24.097,0,43.701,19.597,43.701,43.688v218.611
	c0,10.323-8.399,18.717-18.716,18.717c-10.323,0-18.73-8.394-18.73-18.717V227.048H263.173z"/>
                </svg></i> Attach file</label>
              <code class="file-return"></code>
              <p>The file can be up to 5 mb in size.</p>
            </div>
            <div class="termsAccpt"><label for="terms" class="custom-checkbox">
              <input id="terms" type="checkbox" checked>
              <span class="custom-check"></span> Yes, I understand and agree to the <a href="#">ForceFlexing</a> <a href="#">Terms of Service</a>, including the <a href="#">User Agreement</a> and <a href="#">Privacy Policy</a>.</label></div>
            <div class="job-post-btns">
              <button type="submit" name="submit" class="btn btn-blue">Hire</button>
              <button type="button" class="btn btn-gray">Cancel</button>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>
  </form>
</main>