<?php
class Post {
	private $db;

	public function __construct(){
		$this->db = new Database;
	}

	public function getPosts(){
		$this->db->query("SELECT * FROM `posts`");

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
			$allposts[$key]['image'] = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(APPROOT . $path));
		}
		return ($allposts);
	}

	public function getComments($post_id){
		$this->db->query("SELECT users.user_name AS `name` , comments.comment AS `comment`
		FROM `users` 
		INNER JOIN `comments` ON comments.user_id = users.id 
		WHERE comments.image_id = :post_id
		ORDER BY comments.created_at ASC");

		$this->db->bind(':post_id', $post_id, PDO::PARAM_INT);
		return ($this->db->getAllResult(PDO::FETCH_ASSOC));
	}
}