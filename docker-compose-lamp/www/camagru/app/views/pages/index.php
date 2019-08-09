<?php require_once APPROOT . '/views/include/header.php';?>

	<h1>PAGES/INDEX Hello : <?php echo $data['title']; ?></h1>
	
	<?php if (!empty($data['user_name'])) :?>
		<a href="<?php echo URLROOT . '/pages/add';?>"><button class="button btn-block">add photo</button></a><br>
	<?php endif; ?>

		<?php foreach($data['posts'] as $key => $value) :?>
		<hr class="hr-photos">
		<h3 class="text-center"><?php echo $data['posts'][$key]['user_name'];?></h3>
		<div id="imageholder">
			
			<div class="col">
				<div class="row">
					<img class="imagecenter" src="<?php echo $data['posts'][$key]['image']; ?>">
				</div>


				<div class="row">
					<div class="col margin-aut">
						<input type="submit" value="Like" id="like" class="btn btn-block btn-success">
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
					<h5><strong><?php echo $comment['name'] . ' : ';?></strong><?php echo $comment['comment']; ?></h5>
				</div>
			<?php endforeach;?>
			<div class="newcomment">
				<form method="post" action=<?php echo URLROOT . '/pages/comment'; ?>>
					<textarea name="<?php echo $data['posts'][$key]['image_id']; ?>" col="60" row="5" placeholder="Comment..."></textarea>
					<input type="submit" value="comment" class="btn skyblue btn-block">
				</form>
			</div>
		</div>
		<?php endforeach; ?>
<?php require_once APPROOT . '/views/include/footer.php';?>