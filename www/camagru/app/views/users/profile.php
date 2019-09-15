<?php require APPROOT . '/views/include/header.php';?>

<div class='text-center lead' id='profile-container'> 
<div>
	<img class="profile-photo" alt="profile-picture" src="<?php echo $data['profile_photo'];?>">
</div>
<h1 ><?php echo $data['user_name'] , '\'s '; ?>PROFILE </h1>	
	<?php echo $data['title'] . ' | ' . $data['description'] ?>
</div>

<?php require APPROOT . '/views/include/footer.php'?>
