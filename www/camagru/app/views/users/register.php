<?php require APPROOT . '/views/include/header.php';?>

<div class="box-container text-center">
	<h3>Create new account</h3>
	<form method='post' action=<?php echo URLROOT . '/users/register'; ?>>
			<div class='form-group text-left'><h6 class='ml-1'>User name <sup>*</sup></h6>
			<input class="form-control <?php echo !empty($data['name_error']) ? 'is-invalid' : ''; ?>" type="text" name="user_name" value="<?php echo $data['user_name'];?>"><br>
			<span class='invalid-feedback'><?php echo $data['name_error']; ?></span>
			</div>
			<div class='form-group text-left'><h6 class='ml-1'>Email <sup>*</sup></h6>
			<input class="form-control <?php echo !empty($data['email_error']) ? 'is-invalid' : ''; ?>" type="text" name="email" placeholder='example@gmail.com' value="<?php echo $data['email'];?>"><br>
			<span class='invalid-feedback'><?php echo $data['email_error']; ?></span>
			</div>
			<div class='form-group text-left'><h6 class='ml-1'>Password <sup>*</sup></h6>
			<input class="form-control <?php echo !empty($data['password_error']) ? 'is-invalid' : ''; ?>" type="password" name="password"><br>
			<span class='invalid-feedback'><?php echo $data['password_error']; ?></span>
			</div>
			<div class='form-group text-left'><h6 class='ml-1'>Confirm password <sup>*</sup></h6>
			<input class="form-control <?php echo !empty($data['confirm_password_error']) ? 'is-invalid' : ''; ?>" type="password" name="confirm_password"><br>
			<span class='invalid-feedback'><?php echo $data['confirm_password_error']; ?></span>
			</div>

			<div class="row">
			<div class="col">
			<input type="submit" value="Sign up" class="btn btn-success btn-block">
			</div>
			<div class="col">
			<a href="<?php echo URLROOT . '/users/login'; ?>" class="btn">Already have an account ?</a>
			</div>
			</div>

	</form>
</div>

<?php require APPROOT . '/views/include/footer.php'?>