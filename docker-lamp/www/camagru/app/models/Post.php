<?php
class Post {
	private $db;

	public function __construct(){
		$this->db = new Database;
	}

	public function getPosts(){
		$this->db->query("SELECT image_id, user_id, user_name, image, posts.created_at AS orders 
		FROM posts INNER JOIN users ON posts.user_id = users.id 
		ORDER BY orders DESC");

		$allposts = $this->db->getAllResult(PDO::FETCH_ASSOC);
		foreach ($allposts as $key => $value){
			$path = $value['image'];
			$type = pathinfo($path, PATHINFO_EXTENSION);
			if (file_exists(APPROOT . $path))
				$allposts[$key]['image'] = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(APPROOT . $path));
		}
		return ($allposts);
	}

	public function getNumberOfPages(){
		$this->db->query("SELECT image_id FROM posts");

		$allposts = $this->db->getAllResult(PDO::FETCH_ASSOC);
		return ((int)(ceil($this->db->rowCount() / 5)));
	}

	public function getPostsPaged($offset){
		$this->db->query("SELECT image_id, user_id, user_name, image, posts.created_at AS orders 
		FROM posts INNER JOIN users ON posts.user_id = users.id 
		ORDER BY orders DESC LIMIT :offset,5");

		$this->db->bind(':offset', $offset);
		$allposts = $this->db->getAllResult(PDO::FETCH_ASSOC);
		foreach ($allposts as $key => $value){
			$path = $value['image'];
			$type = pathinfo($path, PATHINFO_EXTENSION);
			if (file_exists(APPROOT . $path))
				$allposts[$key]['image'] = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(APPROOT . $path));
		}
		return ($allposts);
	}

	public function getLikes($image_id){
		$this->db->query("SELECT * FROM likes WHERE image_id = :image_id");
		$this->db->bind(':image_id', $image_id, PDO::PARAM_INT);
		$this->db->execute();
		return ($this->db->rowCount());
	}

	public function likePost($image_id, $user_id){
		$this->db->query("SELECT `id` FROM users WHERE user_name = :user_name");
		$this->db->bind(':user_name', $user_id, PDO::PARAM_STR);
		if (!($user_id = $this->db->getSingleResult()))
			return (false);	
		$user_id = $user_id->id;
		
		$this->db->query("SELECT likes.image_id AS image_id, likes.user_id AS like_user 
		FROM likes  INNER JOIN posts ON likes.image_id = posts.image_id 
		WHERE posts.user_id = $user_id AND likes.image_id = :image_id");
		$this->db->bind(':image_id', $image_id, PDO::PARAM_INT);
		$like = $this->db->getSingleResult();
		if ($like){
			$this->db->query("DELETE FROM likes WHERE user_id = :user_id");
			$this->db->bind(':user_id', $user_id, PDO::PARAM_INT);
			if ($this->db->execute())
				return (true);
			
		} else {
			$this->db->query("INSERT INTO likes (image_id, user_id) VALUES (:image_id, :user_id)");
			$this->db->bind(':user_id', $user_id, PDO::PARAM_INT);
			$this->db->bind(':image_id', $image_id, PDO::PARAM_INT);
			if ($this->db->execute())
				return (true);
		}
		return (false);
	}

	public function deletePostComments($image_id){
		$this->db->query("DELETE FROM `comments` WHERE `image_id` = :image_id");
		$this->db->bind(':image_id', $image_id);
		if ($this->db->execute())
			return (true);
		return (false);
	}

	public function deletePostLikes($image_id){
		$this->db->query("DELETE FROM `likes` WHERE `image_id` = :image_id");
		$this->db->bind(':image_id', $image_id);
		if ($this->db->execute())
			return (true);
		return (false);
	}

	public function getPostPath($image_id){
		$this->db->query("SELECT `image` FROM `posts` WHERE `image_id` = :image_id");
		$this->db->bind(':image_id', $image_id);
		$image = $this->db->getSingleResult();
		if ($image)
			return ($image->image);
		return (false);
	}

	public function deletePost($image_id){
		$path = $this->getPostPath($image_id);
		$this->db->query("DELETE FROM `posts` WHERE `image_id` = :image_id");
		$this->db->bind(':image_id', $image_id);
		if ($this->db->execute()
				&& $this->deletePostComments($image_id)
					&& $this->deletePostLikes($image_id)) {
						if (is_writeable(APPROOT . $path))
							unlink(APPROOT . $path);
						return (true);
		}
		return (false);
	}

	/*
		* checks if the image is originally posted by a user.
	*/
	public function isFromUser($image_id, $user_id){
		$this->db->query("SELECT `image_id`, `user_id` FROM `posts` WHERE image_id = :image_id");
		$this->db->bind(':image_id', $image_id);
		$obj = $this->db->getSingleResult();
		if ($obj && $obj->user_id == $user_id){
			return (true);
		}
		return (false);
	}

	public function getComments($image_id){
		$this->db->query("SELECT users.user_name AS `name` , comments.comment AS `comment`
		FROM `users` 
		INNER JOIN `comments` ON comments.user_id = users.id 
		WHERE comments.image_id = :image_id
		ORDER BY comments.created_at ASC");

		$this->db->bind(':image_id', $image_id, PDO::PARAM_INT);
		return ($this->db->getAllResult(PDO::FETCH_ASSOC));
	}

	public function postExists($image_id){
		$this->db->query("SELECT * FROM `posts` WHERE `image_id` = :image_id");
		$this->db->bind(':image_id', $image_id);
		$image = $this->db->getSingleResult();
		if ($image)
			return (true);
		return (false);
	}

	public function storeComment($image_id, $user_id, $comment){
		try{
		$this->db->query("INSERT INTO `comments` (`image_id`, `user_id`, `comment`) VALUES($image_id, $user_id, :comment)");
		$this->db->bind(':comment', $comment, PDO::PARAM_STR);
		if ($this->db->execute())
			return (true);
		}
		catch (PDOException $e)
		{
			return (false);
		}
	}

	public function storePost($post){
		try{
		$this->db->query("INSERT INTO posts 
		SET	user_id = :user_id, image = :image,
		image_type = :image_type, image_size = :image_size");

		$this->db->bind(':user_id', $post['user_id'], PDO::PARAM_INT);
		$this->db->bind(':image', $post['image'], PDO::PARAM_STR);
		$this->db->bind(':image_type', $post['image_type'], PDO::PARAM_STR);
		$this->db->bind(':image_size', $post['image_size'], PDO::PARAM_INT);
		if ($this->db->execute())
			return (true);
		}
		catch (PDOException $e)
		{
			return (false);
		}
	}

	public function getAllUserPosts($user_name){
		$this->db->query("SELECT image_id, user_id, user_name, image, posts.created_at AS created_at 
		FROM posts INNER JOIN users ON posts.user_id = users.id 
		WHERE users.user_name = :user_name
		ORDER BY posts.created_at DESC");

		$this->db->bind(':user_name', $user_name, PDO::PARAM_STR);
		$allposts = $this->db->getAllResult(PDO::FETCH_ASSOC);
		foreach ($allposts as $key => $value){
			$path = $value['image'];
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$allposts[$key]['image'] = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(APPROOT . $path));
		}
		return ($allposts);
	}

	public function sendNotification($data){
		$this->db->query('SELECT `user_id`, users.user_name FROM posts INNER JOIN users ON `user_id` = users.id 
		WHERE posts.image_id = :image_id');
		$this->db->bind(':image_id', $data['image_id']);
		if (($user_id_name = $this->db->getSingleResult()) && $user_id_name->user_name != $data['user_name']){
			$this->db->query("SELECT * FROM users WHERE `id` = " . $user_id_name->user_id);
			if (($user = $this->db->getSingleResult()) && $user->notification == '1'){
				mail($user->email, 'Camagru Notification', $data['user_name'] . ' has commented on one of your posts.');
				return (true);
			}
		}
		return (false);
	}
}