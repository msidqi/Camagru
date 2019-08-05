<?php
session_start();
class Pages extends Controller {
	private $postModel;

	public function __construct(){
		$this->postModel = $this->model('Post');
	}
	
	public function	delete(){
		$image_id_to_delete = key($_POST);
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			if (isLoggedIn() && $this->postModel->isFromUser($image_id_to_delete, $_SESSION['user_id'])){
				// echo 'DELETED LOL<br>';
				redirect('pages/index');
			}
		}
	}

	public function index(){
		$posts = $this->postModel->getPosts();
		
		foreach($posts as $key => $post){
			$comments = $this->postModel->getComments($post['image_id']); // get comments for this post sorted by creation date.
			$posts[$key]['comments'] = $comments;
		}

		$data = [
			'title'		=> 'index page',
			'posts'		=> $posts,
			'user_name'	=> $_SESSION['user_name'],
		];

		$this->view('pages/index', $data);
	}

	public function comment(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			if (isLoggedIn()){			// added a comment
				$image_id = key($_POST);
				if ($this->postModel->postExists($image_id)){
					$newcomment = filter_var($_POST[$image_id], FILTER_SANITIZE_STRING);
					$this->postModel->storeComment($image_id, $_SESSION['user_id'], $newcomment);
					redirect('pages/index');
				}
			} else {
				$this->view('users/login', $data = []);
			}
		}
		$this->view('users/login', $data = []);
	}

    public function about(){
		$data = [
			'title' => 'about page',
			'description' => 'This is my php camagru-project web application, it\'s an instagram-like app the allows users to customize and take pictures, share, comment, and like other people\'s posts'
		];
		$this->view('pages/about', $data);
	}
}