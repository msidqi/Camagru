<?php require APPROOT . '/views/include/header.php';?>

<div class='box-container text-center'>
	<form  action="/action_page.php">
			<input class='mt-2' type="text" name="fname" placeholder='User name'><br>
			<input class='mt-2' type="text" name="lname" placeholder='email'><br>
			<input class='mt-2' type="password" name="lname" placeholder='password'><br>
			<input class='mt-2' type="password" name="lname" placeholder='confirm password'><br>
			<input class='mt-3' type="submit" value="Create an account">
	</form>


</div>

<?php require APPROOT . '/views/include/footer.php'?>