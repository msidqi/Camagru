<?php require APPROOT . '/views/include/header.php';?>

<div class='text-center lead' id='profile-container'> 
<div>
	<img class="profile-photo" alt="profile-picture" src="https://avatars0.githubusercontent.com/u/42954251?s=400&u=5612301d9351e63844d8dcaa26476b9b57f0b71e&v=4">
</div>
<h1 ><?php echo $data['user_name'] , '\'s '; ?>PROFILE </h1>	
	<?php echo $data['title'] . ' | ' . $data['description'] ?>
</div>

<?php require APPROOT . '/views/include/footer.php'?>


<?php

?>