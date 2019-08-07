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
				if ($this->postModel->deletePost($image_id_to_delete))
					redirect('pages/index');
				else
					echo 'Something went wrong<br>';
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

	public function add(){
		if (!isLoggedIn()){
			$this->view('users/login', $data = []);
		} else {
			$posts = $this->postModel->getAllUserPosts($_SESSION['user_name']);

			$data = [
				'title' => 'add photo page',
				'posts'		=> $posts
			];
			$this->view('pages/addphoto', $data);
		}
	}

	public function upload(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()){
			// var_dump($_FILES);
			if (isset($_FILES['uploadedimage']) && $_FILES['uploadedimage']['error'] === 0){
				$uploads_dir = APPROOT . '/photos/posts/';
				$maxsize = 5 * 1024 * 1024; // 5Mb
				$validextension = [
					"jpg" => "image/jpg",
					"jpeg" => "image/jpeg",
					"gif" => "image/gif",
					"png" => "image/png"
				];
				$extensions = '';
				foreach($validextension as $key => $value){
					$extensions = $key . ' ' . $extensions;
				};
				$imgname = $_FILES['uploadedimage']['name'];
				$imgtype = $_FILES['uploadedimage']['type'];
				$imgsize = $_FILES['uploadedimage']['size'];
				$imgext = pathinfo($imgname, PATHINFO_EXTENSION);

				// var_dump(getimagesize($_FILES["uploadedimage"]["tmp_name"]));

				switch (1337) {
					case !array_key_exists($imgext, $validextension):
						$data['error'] = 'Extensions allowd : ' . $extensions;
						break ;
					case $imgsize > $maxsize:
						$data['error'] = 'File is larger than ' . $maxsize . 'mb.';
						break ;	
					case !in_array($imgtype, $validextension):
						$data['error'] = 'Extensions allowd : ' . $extensions;
						break ;
					default :
						// change file name until it has unique name
						// save file in photos/posts
						// Insert into posts table
						while (file_exists($uploads_dir . ($unique_name = uniqid(substr($_SESSION['user_name'], 0, 3) )) )){

						}
						if (touch($uploads_dir . $unique_name . '.' . $imgext) &&
							move_uploaded_file($_FILES["uploadedimage"]["tmp_name"], $uploads_dir . $unique_name . '.' . $imgext)){
							$post = [
								'user_id'		=> $_SESSION['user_id'],
								'image'			=> '/photos/posts/' . $unique_name . '.' . $imgext,
								'image_type'	=> $imgext,
								'image_size'	=> $imgsize,
							];
							if ($this->postModel->storePost($post)){ // dont forget to edit before storing in db for error handling
								blendImages('megaman', $uploads_dir . $unique_name . '.' . $imgext);
								$data['error'] = '';
					
							}
							else
								$data['error'] = 'File did not get saved.';
						} else
							$data['error'] = 'Permissions not set correctly.';
				}
				
			} else {
				$data = [
					'error' => $_FILES['uploadedimage']['error']
				];
			}
			redirect('pages/add');
			// $this->view('pages/addphoto', $data);
		} else {
			redirect('pages/add');
		}
	}
}