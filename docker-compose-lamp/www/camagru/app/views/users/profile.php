<?php require APPROOT . '/views/include/header.php';?>

<div class='text-center lead' id='profile-container'> 
<div>
	<img class="profile-photo" alt="profile-picture" src="<?php if (!empty($data['profile_photo'])) echo $data['profile_photo'];?>">
</div>

<h1 ><?php if (!empty($data['user_name'])) echo $data['user_name']; ?>'s profile</h1>	
	<?php if (!empty($data['description'])) echo $data['description'] ?>

	<hr><form  class="row" method="POST" action=<?php echo URLROOT . '/users/profile'; ?>>
			<div class="col-6">
				<input class="form-control <?php echo !empty($data['name_error']) ? 'is-invalid' : (!empty($data['name_success']) ? 'is-valid' : ''); ?>" type="text" placeholder="New Username" name="newusername">
			</div>
			<div class="col-4">
				<button class="btn btn-dark" type="submit" >Change Username</button>
				<span class='invalid-feedback'><?php if (!empty($data['name_error'])) echo $data['name_error']; ?></span>
			</div>
		</form>
		<hr><form  class="row" method="POST" action=<?php echo URLROOT . '/users/profile'; ?>>
			<div class="col-6">
				<input class="form-control <?php echo !empty($data['password_error']) ? 'is-invalid' : (!empty($data['password_success']) ? 'is-valid' : ''); ?>" type="password" placeholder="New Password" name="newpassword">
			</div>
			<div class="col-4">
				<button class="btn btn-dark" type="submit">Change Password</button>
				<span class='invalid-feedback'><?php if (!empty($data['password_error'])) echo $data['password_error']; ?></span>
			</div>
		</form>
		
		<hr><form  class="row" method="POST" action=<?php echo URLROOT . '/users/profile'; ?>>
			<div class="col-6">
			<input class="form-control <?php echo !empty($data['email_error']) ? 'is-invalid' : (!empty($data['email_success']) ? 'is-valid' : ''); ?>" type="text" placeholder="New Email" name="newemail">
			</div>
			<div class="col-4">
				<button class="btn btn-dark" type="submit">Change Email</button>
				<span class='invalid-feedback'><?php if (!empty($data['email_error'])) echo $data['email_error']; ?></span>
			</div>
		</form>	
		<hr>
		<form  class="row" method="POST" action=<?php echo URLROOT . '/users/profile'; ?>>
			<div class="col-4">
			<button class="btn <?php if (!empty($data['notification'])) { if ($data['notification'] == 'enabled') echo 'btn-dark'; else echo 'btn-success'; }?>" value="notification" name="notification" type="submit">
			<?php if (!empty($data['notification'])) { if ($data['notification'] == 'enabled') echo 'Disable Notification'; else echo 'Enable Notification'; }?></button>
			</div>
		</form>	
</div>


<?php require APPROOT . '/views/include/footer.php'?>
