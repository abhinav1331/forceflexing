<?php 
if(isset($postedJob)) {
  ?>
  <!-- Email Modal -->
    <div class="modal fade SuccessModel" id="SuccessModel" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="alert alert-success">
            <strong>Success!</strong> Your Job is successfully posted. You will be your profile page in next <span class="counter">5</span> seconds.
          </div>
        </div>
      </div>
    </div>
  <?php
}
if(isset($recommendMessageContact)) {
  ?>
  <!-- Email Modal -->
    <div class="modal fade contact_contractor" id="contact_contractor" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
         <form action="" method="post" name="send-message-contractor-recommended" id="send-message-contractor-recommended">
           <textarea name="sendMessage" id="" cols="30" rows="10" class="form-control"></textarea>
           <input type="hidden" name="recommended-user-id" class="recommended-user-id">
           <input type="hidden" name="employer_id" class="employer_id" value="<?php echo $userId; ?>">
           <input type="submit" name="submit">
         </form>
        </div>
      </div>
    </div>
  <?php
}
 ?>
