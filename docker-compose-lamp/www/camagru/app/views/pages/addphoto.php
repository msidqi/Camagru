<?php require APPROOT . '/views/include/header.php';?>


<div class="text-center" id="add-box">
	<div id="camera-container">
		<video id="video"></video>
		<button id="capture">Take Picture</button>
	</div>

	<canvas id="canvas"></canvas>

	<div class="hundred">
		<img  id="photo">
	</div>
</div>

<?php require APPROOT . '/views/include/footer.php'?>

<script>
	window.addEventListener('load', startup, false);
</script>