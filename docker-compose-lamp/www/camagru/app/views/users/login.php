<?php require APPROOT . '/views/include/header.php';?>

<div class="box-container text-center">
	<h3>Sign in</h3>
	<form method='post' action=<?php echo URLROOT . '/users/login'; ?>>
			<div class='form-group text-left'><h6 class='ml-1'>User name or email</h6>
			<input class="form-control <?php echo (!empty($data['name_error']) || !empty($data['email_error'])) ? 'is-invalid' : ''; ?>" type="text" name="user_name/email" value="<?php if (!empty($data['user_name'])) echo $data['user_name']; else echo $data['email'];?>"><br>
			<span class='invalid-feedback'><?php if(empty($data['name_error'])) echo $data['email_error']; else echo $data['name_error']; ?></span>
			</div>
			<div class='form-group text-left'><h6 class='ml-1'>Password</h6>
			<input class="form-control <?php echo !empty($data['password_error']) ? 'is-invalid' : ''; ?>" type="password" name="password"><br>
			<span class='invalid-feedback'><?php echo $data['password_error']; ?></span>
			</div>

			<div class="row">
			<div class="col">
			<input type="submit" value="Sign in" class="btn btn-success btn-block">
			</div>
			<div class="col">
			<a href="<?php echo URLROOT . '/users/register'; ?>" class="btn">Don't have an account ?</a>
			</div>
			</div>

	</form>
</div>

<?php require APPROOT . '/views/include/footer.php'?>