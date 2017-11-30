<main role="main">
  <section class="inbox-wrap">
    <div class="container">
      <div class="inbox-main">
        <aside class="main-message-body">
		<div class="name-title">
			<?php if(!empty($message_thread) && !empty($message_thread['author_detail'])){ ?>
			<h2><a href="#"><?php echo $message_thread['author_detail']['author_name']; ?></a> <small>1:38 am EDT</small></h2>
            <p><?php echo $message_thread['author_detail']['job_desc']; ?></p>
			<?php } ?>
		</div>
		
          <div class="discussion-box">
            <h3>Discussion</h3>
			<div class="loader" style="display:none;">
				<img src="<?php echo BASE_URL;?>static/images/loading.gif">
			</div>
            <div class="message-body">
				<div id="msg-thread" class="has-scrollbar">
				  <?php
					//store data in hidden
					if(!empty($message_thread['message_detail']))
					{
						?>
						<input type="hidden" id="conv_data" data-attr-con="<?php echo $message_thread['message_detail']['convers_id']; ?>" data-attr-job="<?php echo $message_thread['message_detail']['job_id'];?>" data-attr-sender="<?php echo $message_thread['message_detail']['sender_id']; ?>" data-attr-rec="<?php echo $message_thread['message_detail']['rec_id']; ?>"data-attr-offset=<?php echo $message_thread['offset'];?>"> 
						<?php
					}
					?>
				  <?php if(!empty($message_thread) && !empty($message_thread['messages']))
					{
						foreach($message_thread['messages'] as $msg)
						{
							if(!empty($msg['sender_img']))
							{
								if($msg['role']== 3)
									$imgsrc=BASE_URL.'static/images/contractor/'.$msg['sender_img'];
								elseif($msg['role']== 2)
									$imgsrc=BASE_URL.'static/images/employer/'.$msg['sender_img'];
							}
							else
							{
								$imgsrc=BASE_URL.'static/images/avatar-icon.png';
							}
						?>
							<div class="message-text">
								<?php
								//check if  user is currently active or not
								$instance->settimezone();
								$last_login_time=$msg['last_login_time'];
								if(strtotime($last_login_time) < strtotime("-10 minutes"))
								{
									$avail="offline";
								}
								elseif($msg['visi'] == "offline")
								{
									$avail="offline";
								}
								else
								{
									$avail="available";
								}
								
								?>
							
								<figure class="msg-user-img <?php echo $avail;?> <?php echo $msg['us']; ?>">
									<img src="<?php echo $imgsrc;?>">
								</figure>
								<time class="datestamp"> 
									<span class="time"><?php echo date('H:m a',$msg['message_time']) ;?></span>
									<span class="date"><?php echo date('d/m/Y',$msg['message_time']) ;?></span>
								</time>
								<figcaption>
								  <h4><?php echo $msg['sender_name'];?></h4>
								  
								  <p><?php echo $msg['mesg']; ?><?php echo $msg['attachment']; ?></p>
								  <!--<div class="goto-proposal"><a href="#">Go to the proposal</a></div>-->
								</figcaption>
								<div id="test"></div>
							</div>
			<?php  		}
					} 
			  ?>
			 </div>
              <div class="msg-type-area">
				<div id="previewholder" style="display:none">
					<div class="progress">
					   <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
						 Uploading...
					   </div>
					</div>
				</div>
                <div class="chat-options"> 
					<a class="settings" href="javascript:void(0);"></a> 
					<input id="upload" type="file" style="display:none"/>
					<a class="attachment" id="attach"  href="javascript:void(0);"></a>
					<a class="smileys" id="create" href="javascript:void(0);"></a>
				</div>
				 <input type="hidden" id="message_attachment" value="">
                 <textarea id="text-custom-trigger" class="emojiable-question"></textarea>
				 
              </div>
              <input name="" type="submit" class="submit-btn" value="Submit">
            </div>
          </div>
        </aside>
        <aside class="inbox-sidebar">
          <div class="sidebar-hdr"> <a class="settings" href="javascript:void(0);"></a>
            <div class="toggle-chat-contact"> 
				<a class="chat-area active" href="javascript:void(0);"></a> 
				<a class="contact-area" href="javascript:void(0);"></a> 
			</div>
			<?php if($instance->udata['role'] == 2){ ?>
            <a class="add-more"  href="javascript:void(0);" data-toggle="modal" data-target="#create-conversation"></a> 
			<?php } ?>
			</div>
          <div class="msg-search">
            <form action="">
				<!--<input name="" id="myInput" type="text" placeholder="Search"  onkeyup="myFunction(this)">-->
				<input name="" id="myInput" type="text" placeholder="Search">
              <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
          </div>

          <section class="msg-list">
          <div class="sort-by-element">
          <form>
          <select id="sort_con" name="">
            <option value="desc">Recent</option>
            <option value="asc">Older</option>
          </select>
          </form>
          </div>
          <div class="conversation-list has-scrollbar">
		  <?php 
			if(!empty($conversation_thread))
				{
					$i=1;
					foreach($conversation_thread as $con)
					{
						?>
						<article class="convo-box <?php if($i==1) echo 'active';?>">
							<div class="convo-hdr">
								<h4 class="SectionAble"><a id="load_messages" data-attr-con="<?php echo $con['conv_id']; ?>" data-attr-job="<?php echo $con['job_id']; ?>" href="javascript:void(0);" class="SectionAbleClass"><?php echo $con['opponent_user'] ?></a></h4>
								<time class="datestamp"> <span class="time"><?php echo date('h:m a',$con['msg_time']); ?></span> </time>
							</div>
							<?php if(!empty($con['job_title'])) { ?>
								<p><strong><?php echo $con['job_title']; ?></strong></p>
							<?php } ?>
							<p><span class="msr-recipient"><?php echo $con['last_msg_by'] ?>:</span> <?php echo $con['message'] ?></p>
						</article>
						<?php
						$i++;
					}
				}
			  ?>
		  </div>
          </section>
        </aside>
      </div>
    </div>
  </section>
</main>

<div class="modal fade" id="create-conversation" role="dialog">
	<div class="modal-dialog">
		<!-- Modal for new conversation-->
		<div class="modal-content">
			<div class="modal-body custom-popup">
				<a href="javascript:void(0);" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
				<h2>Start a new conversation</h2>
				<div class="location">
					<input type="hidden" data-user_role="<?php echo $instance->udata['role']; ?>" id="user_role">
					<div class="row">
						<div class="col-md-6">
							
							<?php
								$role=$instance->udata['role']; 
								$select_text="";
								if($role == 3)
									$select_text="Select Employer";
								elseif($role == 2)
									$select_text="Select Contractor";
							?>
							<select id="opponent_user_id" class="input small">
										<option value=""><?php echo $select_text; ?></option>
								<?php 
									if(!empty($opponent_users))
									{
										foreach($opponent_users as $oppo)
										{
										?>
										<option value="<?php echo $oppo['id'] ?>"><?php echo $oppo['first_name']. ' '.$oppo['last_name'];?></option>
										<?php
										}
									}
								?>
							</select>
							<input id="selected-opponent" value="" type="hidden">
						</div>
						<div class="col-md-6">
							<input  id="selected-job-id" value="" type="hidden">
							<select id="ff_jobs" class="input small">
								<?php if(!empty($jobs_of_employer))
										echo $jobs_of_employer;
									?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<textarea placeholder="Message" class="input" id="new_con_message"></textarea>
						</div>
					</div>
				</div>
				<button type="button" class="btn-blue" id="new_conversation">Add</button>
			</div>
		</div>
	</div>
</div>
