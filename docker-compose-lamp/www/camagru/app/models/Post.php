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

		/*$allposts = $this->db->getAllResult(); // FETCH_OBJ
		foreach ($allposts as $post){
			$path = $post->image;
			$type = pathinfo($path);
			$post->image = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(APPROOT . $path));
		}*/

		$allposts = $this->db->getAllResult(PDO::FETCH_ASSOC);
		foreach ($allposts as $key => $value){
			$path = $value['image'];
			$type = pathinfo($path, PATHINFO_EXTENSION);
			// $allposts[$key]['user_id'] = $this->db->quer;
			$allposts[$key]['image'] = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(APPROOT . $path));
		}
		// var_dump($allposts);
		return ($allposts);
	}

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
}