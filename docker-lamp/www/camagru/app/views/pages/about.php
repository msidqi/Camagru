<?php require APPROOT . '/views/include/header.php';?>


<div id="about-container" class="text-center lead b70box"> 
	<h1 style="margin : 30px auto">ABOUT US <?php if (!empty($data['title'])) echo $data['title']; ?></h1>	
	<h4><?php if (!empty($data['description'])) echo $data['description'] ?></h4>
</div>

<?php require APPROOT . '/views/include/footer.php'?>