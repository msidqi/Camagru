<?php require APPROOT . '/views/include/header.php';?>

<div class="box-container text-center">
	<h3>Password reset</h3>
	<form method='post' action=<?php echo URLROOT . '/users/resetpassword'; ?>>
			<div class='form-group text-left'><h6 class='ml-1'>Email</h6>
			<input class="form-control <?php echo (!empty($data['email_error'])) ? 'is-invalid' : (!empty($data['email_success']) ? 'is-valid' : '') ; ?>" type="text" name="email" value="<?php if (!empty($data['email'])) echo $data['email'];?>"><br>
			<span class='invalid-feedback'><?php if(!empty($data['email_error'])) echo $data['email_error'];?></span>
			</div>

			<div class="row">
			<div class="col">
				<input type="submit" value="Send password-reset mail" class="btn btn-success btn-block">
			</div>
			<div class="col">
				<a href="<?php echo URLROOT . '/users/register'; ?>" class="btn">Don't have an account ?</a>
			</div>
			</div>
	</form>
</div>

<?php require APPROOT . '/views/include/footer.php'?>