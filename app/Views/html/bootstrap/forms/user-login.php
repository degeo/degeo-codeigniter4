<form action="<?php echo site_url( uri_string() ); ?>" method="post">

	<div class="form-group">
		<label for="user_email">Email address</label>
		<input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter email" value="<?php echo set_value( 'user_email' ); ?>"/>
	</div>

	<div class="form-group">
		<label for="user_password">Password</label>
		<input type="password" class="form-control" id="user_password" name="user_password" aria-describedby="passwordHelp" placeholder="Password"/>
		<small id="passwordHelp" class="form-text text-muted">Never share your password with anyone else.</small>
	</div>

	<div class="form-check">
		<input type="checkbox" class="form-check-input" id="user_remembered" name="user_remembered"/>
		<label class="form-check-label" for="user_remembered">Remember me</label>
	</div>

	<button type="submit" class="btn btn-primary">Login</button>
	<?php echo csrf_field(); ?>

</form>