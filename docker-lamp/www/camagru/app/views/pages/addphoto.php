<?php require APPROOT . '/views/include/header.php';?>


<div class="text-center" id="add-box">
	<div class="row">

		<div class="col-8">
			<div id="camera-container" class="row margin-auto">
				<video id="video"></video>
				<button id="capture">Take Picture</button>
				<input class="col" type="submit" id="pic" value="Upload Picture!" name="1">
			</div>

			<div class="hundred row margin-auto">
				<img id="preview">
			</div>
				<h4><?php if (!empty($data['error'])) echo $data['error']; ?></h4>

			<div class="row margin-auto">
				<form class="row" method="post" action="<?php echo URLROOT . '/pages/upload'; ?>" enctype="multipart/form-data">
					<input type="file" class="col"  name="uploadedimage" id="uploadedimage" accept="image/*">
					<input class="col" type="submit" value="Upload File!" id="sticker" name="1">
				</form>
			</div>
			<div class="row">
			<div class="col"><input class="sticker" type="image" src="<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/megaman.png')); ?>" /></div>
			<div class="col"><input class="sticker" type="image" src="<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/melon.png')); ?>" /></div>
			<div class="col"><input class="sticker" type="image" src="<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/rajang.png')); ?>" /></div>
			<div class="col"><input class="sticker" type="image" src="<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/ayano.png')); ?>" /></div>
			<div class="col"><input class="sticker" type="image" src="<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/scumbag.png')); ?>" /></div>
			</div>
		</div>

		<canvas id="canvas"></canvas>

		<div class="col-4 personal-gallery">
<?php if (!empty($data['posts'])) : foreach($data['posts'] as $post) : ?>
<div class="previewholder" name="<?php echo $post['image_id']; ?>">
	
		<div class="hundred row margin-auto wrapperr">
				<img class="photo margin-auto" src="<?php echo $post['image']; ?>">
				<div class="buttonn">
					<input type="submit" value="Delete" id="delete2" name="<?php echo $post['image_id']; ?>" class="btn btn-danger btn-sm deleteb">
				</div>
		</div>
</div>
<?php endforeach ; endif ; ?>
		</div>
	</div>
</div>

<?php require APPROOT . '/views/include/footer.php'?>

<script>
var im = [
	'<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/megaman.png')); ?>',
	'<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/melon.png')); ?>',
	'<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/rajang.png')); ?>',
	'<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/ayano.png')); ?>',
	'<?php echo 'data:image/png;base64,' . base64_encode(file_get_contents('../app/photos/superpos/scumbag.png')); ?>'
];
window.addEventListener('load', startup, false);


</script>