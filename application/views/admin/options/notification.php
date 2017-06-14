<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Add Notification</h1>
				<form method="POST">
					<div class="form-group">
						<label>Notification Title</label>
						<input type="text" name="title" class="form-control" placeholder="Enter Notification Title">
					</div>
					<div class="form-group">
						<label>Notification Message</label>
						<textarea name="message" class="form-control" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label>Notification Icon</label>
						<select class="form-control" data-size="5">
						<div class="row">
							<div class="col-sm-6">
								<option class="fa fa-sitemap fa-fw">Ketchup</option>
								<option><i class="fa fa-envelope fa-fw"></i>Ketchup</option>
							</div>
							<div class="col-sm-6">
								<option data-icon="glyphicon-heart">Ketchup</option>
								<option data-icon="glyphicon-heart">Ketchup</option>
							</div>
						</div>
						  
						</select>
						
					</div>		
				<form>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->