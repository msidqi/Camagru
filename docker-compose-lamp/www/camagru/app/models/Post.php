<?php
class Post {
	private $db;

	public function __construct(){
		$this->db = new Database;
	}

	public function getPosts(){
		$this->db->query("SELECT image_id, user_id, user_name, image, posts.created_at AS created_at 
		FROM posts INNER JOIN users ON posts.user_id = users.id 
		ORDER BY posts.created_at DESC");

		$allposts = $this->db->getAllResult(PDO::FETCH_ASSOC);
		foreach ($allposts as $key => $value){
			$path = $value['image'];
			$type = pathinfo($path, PATHINFO_EXTENSION);
			if (file_exists(APPROOT . $path))
				$allposts[$key]['image'] = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(APPROOT . $path));
		}
		return ($allposts);
	}

	public function deletePost($image_id){
		$this->db->query("DELETE FROM `posts` WHERE `image_id` = :image_id");
		$this->db->bind(':image_id', $image_id);
		if ($this->db->execute())
			return (true);
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
		
		$this->db->query("INSERT INTO `comments` (`image_id`, `user_id`, `comment`) VALUES($image_id, $user_id, :comment)");
		$this->db->bind(':comment', $comment, PDO::PARAM_STR);

		if ($this->db->execute())
			return (true);
		return (false);
	}

	public function storePost($post){
		$this->db->query("INSERT INTO posts 
		SET	user_id = :user_id,
		image = :image,
		image_type = :image_type,
		image_size = :image_size");

		$this->db->bind(':user_id', $post['user_id'], PDO::PARAM_INT);
		$this->db->bind(':image', $post['image'], PDO::PARAM_STR);
		$this->db->bind(':image_type', $post['image_type'], PDO::PARAM_STR);
		$this->db->bind(':image_size', $post['image_size'], PDO::PARAM_INT);

		if ($this->db->execute())
			return (true);
		return (false);
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
}