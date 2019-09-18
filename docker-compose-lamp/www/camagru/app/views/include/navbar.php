<div class="navbar">
	<a id='sitename' href=<?php echo URLROOT;?>> <?php echo SITENAME ?> </a>
	<?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_name']) && !empty($_SESSION['user_email'])) : ?>

	<div class='btns'>
		<a href="<?php echo URLROOT . '/users/logout'; ?>"><button class='button'>Logout</button></a>
	</div>
	<div class='btns'>
		<a href="<?php echo URLROOT . '/users/profile'; ?>"><button class='button'>Profile</button></a>
	</div>

	<?php else : ?>
	<div class='btns'>
		<a href="<?php echo URLROOT . '/users/login'; ?>"><button class='button'>Sign in</button></a>
	</div>
	<div class='btns'>
		<a href="<?php echo URLROOT . '/users/register';?>"><button class='button'>Sign up</button></a>
	</div>
	<?php endif; ?>

	<div class='btns'>
		<a href="<?php echo URLROOT . '/pages/about';?>"><button class='button'>About</button></a>
	</div>
</div>