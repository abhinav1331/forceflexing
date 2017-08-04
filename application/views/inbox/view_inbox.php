<main role="main">
  <section class="inbox-wrap">
    <div class="container">
      <div class="inbox-main">
        <aside class="main-message-body">
          <div class="name-title">
            <h2><a href="#">William Bach</a> <small>1:38 am EDT</small></h2>
            <p>I need to build a ticket marketplace website</p>
          </div>
          <div class="discussion-box">
            <h3>Discussion</h3>
            <div class="message-body">
              <div class="message-text">
                <figure class="msg-user-img available"><img src="<?php echo BASE_URL;?>/static/images/inbox-user-img.jpg"></figure>
                <time class="datestamp"> <span class="time">9:45 am</span> <span class="date">12/08/2016</span> </time>
                <figcaption>
                  <h4>William Bach</h4>
                  <p>Hello,<br>
                    <br>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
                    esse cillum dolore eu fugiat nulla pariatur.</p>
                  <div class="goto-proposal"><a href="#">Go to the proposal</a></div>
                </figcaption>
              </div>
              <div class="message-text">
                <figure class="msg-user-img available"><img src="<?php  echo BASE_URL;?>/static/images/inbox-user-img2.jpg"></figure>
                <time class="datestamp"> <span class="time">9:45 am</span> <span class="date">12/08/2016</span> </time>
                <figcaption>
                  <h4>Steve Smith</h4>
                  <p>Hello William,<br>
                    <br>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua.<br>
                    <br>
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                    nulla pariatur.</p>
                </figcaption>
              </div>
              <div class="message-text">
                <figure class="msg-user-img available"><img src="<?php  echo BASE_URL;?>/static/images/inbox-user-img.jpg"></figure>
                <time class="datestamp"> <span class="time">9:45 am</span> <span class="date">12/08/2016</span> </time>
                <figcaption>
                  <h4>William Bach</h4>
                  <p>Hello,<br>
                    <br>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam.</p>
                </figcaption>
              </div>
              <div class="msg-type-area">
                <div class="chat-options"> <a class="settings" href="javascript:void(0);"></a> <a class="attachment" href="javascript:void(0);"></a> <a class="smileys" href="javascript:void(0);"></a></div>
                <textarea name="" cols="" rows=""></textarea>
              </div>
              <input name="" type="submit" class="submit-btn">
            </div>
          </div>
        </aside>
        <aside class="inbox-sidebar">
          <div class="sidebar-hdr"> <a class="settings" href="javascript:void(0);"></a>
            <div class="toggle-chat-contact"> <a class="chat-area active" href="javascript:void(0);"></a> <a class="contact-area" href="javascript:void(0);"></a> </div>
            <a class="add-more" href="javascript:void(0);"></a> </div>
          <div class="msg-search">
            <form action="">
              <input name="" type="text" placeholder="Search">
              <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
          </div>

          <section class="msg-list">
          <div class="sort-by-element">
          <form>
          <select name="">
            <option>Recent</option>
            <option>Older</option>
          </select>
          </form>
          </div>
          <div class="conversation-list">
		  <?php 
			if(!empty($conversation_thread))
				{
					$i=1;
					foreach($conversation_thread as $con)
					{
						?>
						<article class="convo-box <?php if($i==1) echo 'active';?>">
							<div class="convo-hdr">
								<h4><a href="#"><?php echo $con['opponent_user'] ?></a></h4>
								<time class="datestamp"> <span class="time"><?php echo date('h:m a',$con['msg_time']); ?></span> </time>
							</div>
							<p><strong><?php echo $con['job_title']; ?></strong></p>
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
