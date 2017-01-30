<div class="account-container stacked">
	
	<div class="content clearfix">
		
		<form action="<?php echo base_url('login/process_login'); ?>" method="post">
		
			<h1>Log in</h1>		
			
			<div class="login-fields">
				
				<p>Log in using your registered account:</p>
				<?php if (!empty($info)) {
					echo '
						<div class="alert alert-warning alert-dismissible fade in" role="alert">
						    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						    <strong>'.$info.'</strong>
						</div>
						';
				} ?>
				<div class="field">
					<label for="username">Username:</label>
					<input type="text" name="username" placeholder="Username" class="form-control input-lg username-field" required/>
				</div> <!-- /field -->
				
				<div class="field">
					<label for="password">Password:</label>
					<input type="password" name="password" placeholder="Password" class="form-control input-lg password-field" required/>
				</div> <!-- /password -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
				<button class="login-action btn btn-primary">Log in</button>
				
			</div> <!-- .actions -->
			
		</form>
		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->
