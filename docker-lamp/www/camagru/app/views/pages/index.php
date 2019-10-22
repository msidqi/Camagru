<?php require_once APPROOT . '/views/include/header.php'; ?>

	<?php if (!empty($data['user_name'])) :?>
		<a href="<?php echo URLROOT . '/pages/add';?>"><button id="addphoto" class="button peach-gradient btn-block">add photo</button></a><br>
	<?php endif; ?>

		<?php foreach($data['posts'] as $key => $value) :?>
	<?php if ($key > 0) : ?> <hr class="hr-photos"> <?php endif; ?>
		<h3 class="text-center"><?php echo $data['posts'][$key]['user_name'];?></h3>
		<div id="imageholder">
			
			<div class="col">
				<div class="row">
					<img class="imagecenter" src="<?php echo $data['posts'][$key]['image']; ?>">
				</div>


				<div class="row">
					<div class="col margin-aut">
						<input type="submit" value="<?php if ($data['posts'][$key]['likes'] > 0) echo $data['posts'][$key]['likes']; else echo 'Like';?>" class="btn btn-success like" name="<?php echo $data['posts'][$key]['image_id']; ?>">
					</div>
					<?php if ($data['user_name'] == $data['posts'][$key]['user_name']) : ?>
					<div class="col margin-aut">
						<form method='post' action=<?php echo URLROOT . '/pages/delete'; ?>>	
						<input type="submit" value="Delete" id="delete" name="<?php echo $data['posts'][$key]['image_id']; ?>" class="btn btn-danger">
						</form>
					</div>
					<?php endif; ?>
				</div>
			</div>
			
			
		</div>
		<hr class="hr-photos">
		<div class="commentsbox">
			<?php foreach($data['posts'][$key]['comments'] as $comment) : ?>
				<div class="comment">
					<h6><strong><?php echo $comment['name'] . ' : ';?></strong><?php echo $comment['comment']; ?></h5>
				</div>
			<?php endforeach;?>
			<div class="newcomment">
				<form method="post" action=<?php echo URLROOT . '/pages/comment'; ?>>
					<textarea class="faded" name="<?php echo $data['posts'][$key]['image_id']; ?>" col="60" row="5" placeholder="your comment..."></textarea>
					<input type="submit" value="comment" class="btn font newcombox btn-block">
				</form>
			</div>
		</div>
		<?php endforeach; ?>
		
		<div class="paging-container row">
		<?php for ($i = 0; $i < $data['number_of_pages']; $i++) :?>
			<?php if ($data['current_page'] == $i + 1) : ?>
				<form method="GET">
					<button class="paging-number-current btn" type="submit" name="page" value="<?php echo $i + 1?>"><?php echo $i + 1 ?></button>
				</form>
			<?php else : ?>
				<form method="GET">
					<button class="paging-number btn" type="submit" method="GET" name="page" value="<?php echo $i + 1?>"><?php echo $i + 1 ?></button>
				</form>
			<?php endif; ?>
		<?php endfor; ?>
		</div>
<?php require_once APPROOT . '/views/include/footer.php';?>

<script>
window.addEventListener('load', function(){
	var likes = document.getElementsByClassName('like');

	for (var i = 0; i < likes.length; i++){
		
		likes[i].addEventListener('click', function(){
			var fd = new FormData();
			fd.append("image_id", this.getAttribute('name'));
			fd.append("current_user", "<?php if (!empty($_SESSION['user_name']))echo $_SESSION['user_name']; ?>")
			var xhr = new XMLHttpRequest();
			xhr.parent = this;
			xhr.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					if (this.responseText == 1) {
						if (this.parent.value == 'Like')
							this.parent.value = 1;
						else
							this.parent.value += 1;
					}
					else if (this.responseText == -1) {
						if (this.parent.value == 1 || this.parent.value <= 0)
							this.parent.value = 'Like';
						else
							this.parent.value -= 1;
					}
				}
			}
			xhr.open("POST", 'https://vmod.dev/camagru/pages/like');
			xhr.send(fd);
		}, false);
	}
}, false);
</script>
