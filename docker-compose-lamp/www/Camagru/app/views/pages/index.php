<?php require_once APPROOT . '/views/include/header.php';?>

	<h1>PAGES/INDEX Hello : <?php echo $data['title']; ?></h1>
	<ul>
		<?php foreach($data['posts'] as $user) :?>
		
		<li> <?php echo $user->login ?> </li>
		
		<?php endforeach; ?>
	</ul>
<?php require_once APPROOT . '/views/include/footer.php';?>