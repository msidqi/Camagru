<?php require APPROOT . '/views/include/header.php';?>


<div class="text-center" id="add-box">
	<div class="row">

		<div class="col">
			<div id="camera-container" class="row margin-auto">
				<video id="video"></video>
				<button id="capture">Take Picture</button>
				<input class="col" type="submit" name="1" id="pic" value="Upload" name="submit">
			</div>

			<div class="hundred row margin-auto">
				<img id="preview">
			</div>

			<div class="row margin-auto">
				<h4><?php if ($data['error'] != 0) echo 'Error : ' , $data['error']; ?></h4>
				<form method="post" action="<?php echo URLROOT . '/pages/upload'; ?>" enctype="multipart/form-data">
					<input type="file" class="col"  name="uploadedimage" id="uploadedimage" accept="image/*">
					<input class="col" type="submit" value="Upload" name="3">
				</form>
			</div>
		</div>
		
		<canvas id="canvas"></canvas>

		<div class="col">
<?php if (!empty($data['posts'])) : foreach($data['posts'] as $post) : ?>
			<div class="hundred row margin-auto">
				<img class="photo margin-auto" src="<?php echo $post['image']; ?>">
			</div>
<?php endforeach ; endif ; ?>
		</div>
	</div>
</div>

<?php require APPROOT . '/views/include/footer.php'?>

<script>
	window.addEventListener('load', startup, false);

document.getElementById("pic").addEventListener('click', function(e) {
	var fd = new FormData();
	fd.append("image", img);
	var xhr = new XMLHttpRequest();

	// xhr.addEventListener('load', function (e) {
	// 	alert('hello world');
	// })
	xhr.open("POST", 'http://localhost/camagru/pages/upload');
	xhr.send(fd);
}, false);
</script>