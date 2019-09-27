<div class="navbar">
	<a id='sitename' href=<?php echo URLROOT;?>> <?php echo SITENAME ?> </a>
	<?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_name']) && !empty($_SESSION['user_email'])) : ?>
	<div class='row'>
		<div class='col'>
			<a href="<?php echo URLROOT . '/users/logout'; ?>"><button class='button btn-light'>Logout</button></a>
		</div>
		<div class='col'>
			<a href="<?php echo URLROOT . '/users/profile'; ?>"><button class='button btn-light'>Profile</button></a>
		</div>

		<?php else : ?>
		<div class='col'>
			<a href="<?php echo URLROOT . '/users/login'; ?>"><button class='button btn-light'>Sign in</button></a>
		</div>
		<div class='col'>
			<a href="<?php echo URLROOT . '/users/register';?>"><button class='button btn-light'>Sign up</button></a>
		</div>
		<?php endif; ?>

		<div class='col'>
			<a href="<?php echo URLROOT . '/pages/about';?>"><button class='button btn-light'>About</button></a>
		</div>
	</div>
</div>