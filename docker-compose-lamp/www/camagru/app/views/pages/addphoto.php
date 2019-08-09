<?php require APPROOT . '/views/include/header.php';?>


<div class="text-center" id="add-box">
	<div class="row">

		<div class="col">
			<div id="camera-container" class="row margin-auto">
				<video id="video"></video>
				<button id="capture" >Take Picture</button>
				<input class="col" type="submit" id="pic" value="Upload" name="1">
			</div>

			<div class="hundred row margin-auto">
				<img id="preview">
			</div>

			<div class="row margin-auto">
				<h4><?php echo $data['error']; ?></h4>
				<form method="post" action="<?php echo URLROOT . '/pages/upload'; ?>" enctype="multipart/form-data">
					<input type="file" class="col"  name="uploadedimage" id="uploadedimage" accept="image/*">
					<input class="col" type="submit" value="Upload" id="sticker" name="4">
				</form>
			</div>
			<div class="row">
			<div class="col"><input class="sticker" type="image" src="http://i.imgur.com/RV2a28T.png" /></div>
			<div class="col"><input class="sticker" type="image" src="http://3.bp.blogspot.com/-GOT_6c04LPY/U9uA7aZqWmI/AAAAAAAAAZw/4hCLLQh_CJk/s1600/j.png" /></div>
			<div class="col"><input class="sticker" type="image" src="http://img3.wikia.nocookie.net/__cb20140805014958/monsterhunter/images/e/e6/MH10th-Rajang_Icon.png" /></div>
			<div class="col"><input class="sticker" type="image" src="https://shortcut-test2.s3.amazonaws.com/uploads/project/attachment/8605/default_chibiyandere.png" /></div>
			<div class="col"><input class="sticker" type="image" src="https://i.imgur.com/ZZZ2VUn.png" /></div>
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
</script>