<main role="main">
  <section class="notifications-wrap">
    <div class="container">
      <div class="all-notifs">
		<h3>Notifications</h3>
		<?php echo $instance->Notification->get_all_notifications($userid); ?>
      </div>
    </div>
  </section>
</main>