<?php require APPROOT . '/views/include/header.php';?>

<div class="box-container text-center">
	<h3>Change Password</h3>
	<form method='post' action=<?php echo URLROOT . '/users/changepassword'; ?>>
            <div class='form-group text-left'><h6 class='ml-1'>Password <sup>*</sup></h6>
			<input class="form-control <?php echo !empty($data['password_error']) ? 'is-invalid' : ''; ?>" type="password" name="password"><br>
			<span class='invalid-feedback'><?php if (!empty($data['password_error'])) echo $data['password_error']; ?></span>
			</div>
			<div class='form-group text-left'><h6 class='ml-1'>Confirm password <sup>*</sup></h6>
			<input class="form-control <?php echo !empty($data['confirm_password_error']) ? 'is-invalid' : ''; ?>" type="password" name="confirm_password"><br>
			<span class='invalid-feedback'><?php if (!empty($data['confirm_password_error'])) echo $data['confirm_password_error']; ?></span>
			</div>

			<div class="row">
			<div class="col">
				<input type="submit" value="Confirm" class="btn btn-success btn-block">
			</div>
			</div>
	</form>
</div>

<?php require APPROOT . '/views/include/footer.php'?>