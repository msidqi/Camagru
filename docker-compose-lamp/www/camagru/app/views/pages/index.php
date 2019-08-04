<?php require_once APPROOT . '/views/include/header.php';?>

	<h1>PAGES/INDEX Hello : <?php echo $data['title']; ?></h1>
	<ul>
		<?php foreach($data['posts'] as $key => $value) :?>
		
		<div id="imageholder">
		<img class="imagecenter" src="<?php echo $data['posts'][$key]['image']; ?>">
		</div>
		<div class="commentsbox">
			<?php foreach($data['posts'][$key]['comments'] as $comment) : ?>
				<div class="comment">
					<h5><?php echo $comment['name'] . ' : ' . $comment['comment'] ?></h5>
				</div>
			<?php endforeach;?>
			<div class="newcomment">
				<form>
					<textarea name="newcomment" col="60" row="5" placeholder="Comment..."></textarea>
					<input type="submit" value="comment" class="btn skyblue btn-block">
				</form>
			</div>
		</div>
		
		<?php endforeach; ?>
	</ul>
<?php require_once APPROOT . '/views/include/footer.php';?>