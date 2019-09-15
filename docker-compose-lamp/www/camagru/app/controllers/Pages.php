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
		} else {
			redirect('users/login');
		}
	}

	public function index(){
		$posts = $this->postModel->getPosts();

		foreach($posts as $key => $post){
			$comments	= $this->postModel->getComments($post['image_id']); // get comments for this post sorted by creation date ASC.
			$likes		= $this->postModel->getLikes($post['image_id']);
			$posts[$key]['comments'] = $comments;
			$posts[$key]['likes'] = $likes;
		}

		$data = [
			'title'		=> 'index page',
			'posts'		=> $posts,
			'user_name'	=> $_SESSION['user_name'],
		];

		$this->view('pages/index', $data);
	}

	public function like(){
		if ($_SERVER['REQUEST_METHOD'] == "POST"
				&& isset($_POST['image_id']) && isset($_POST['current_user'])){
			$this->postModel->likePost($_POST['image_id'], $_POST['current_user']);
			return (true);
		}
		else
			redirect('users/login');
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
				redirect('users/login');
			}
		}
		else
			redirect('users/login');
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
			redirect('users/login');
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
			$sticker = null;
			$unique_name = uniqid(substr($_SESSION['user_name'], 0, 3));
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
			if (key($_POST) == 'image' && substr($_POST['image'], 0, 22) === 'data:image/png;base64,'
				&& isset($_POST['name']) && $_POST['name'] > 0 && $_POST['name'] < 6){
				$sticker = getStickerName($_POST['name']);
				$decodedimg = base64_decode(substr($_POST['image'], 22, strlen($_POST['image'])));
				$ex = substr($decodedimg , 1, 3);
				$size = filesize(APPROOT . '/photos/posts/' . $unique_name . '.png');
				if ($ex == 'PNG' && $size < $maxsize){
					file_put_contents(APPROOT . '/photos/posts/' . $unique_name . '.png', $decodedimg);
					$postpic = [
						'user_id'		=> $_SESSION['user_id'],
						'image'			=> '/photos/posts/' . $unique_name . '.png',
						'image_type'	=> 'png',
						'image_size'	=> $size,
					];					
					if (blendImages($sticker, APPROOT . '/photos/posts/' . $unique_name . '.png')
						&& $this->postModel->storePost($postpic)){
						$data['error'] = '';
					}
				}
			} else if (isset($_FILES['uploadedimage']) && $_FILES['uploadedimage']['error'] === 0
					&& isset($_FILES['uploadedimage']['name']) && isset($_FILES['uploadedimage']['type'])
						&& isset($_FILES['uploadedimage']['size']) && key($_POST) > 0 && key($_POST) < 6){
				$sticker = getStickerName(key($_POST));
				$imgname = $_FILES['uploadedimage']['name'];
				$imgtype = $_FILES['uploadedimage']['type'];
				$imgsize = $_FILES['uploadedimage']['size'];
				$imgext = pathinfo($imgname, PATHINFO_EXTENSION);
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
						while (file_exists($uploads_dir . $unique_name )){

						}
						if (touch($uploads_dir . $unique_name . '.' . $imgext) &&
							move_uploaded_file($_FILES["uploadedimage"]["tmp_name"], $uploads_dir . $unique_name . '.' . $imgext)){
							$post = [
								'user_id'		=> $_SESSION['user_id'],
								'image'			=> '/photos/posts/' . $unique_name . '.' . $imgext,
								'image_type'	=> $imgext,
								'image_size'	=> $imgsize,
							];
							if (blendImages($sticker, $uploads_dir . $unique_name . '.' . $imgext)
								&& $this->postModel->storePost($post)){
								$data['error'] = '';
							}
							else
								$data['error'] = 'File was not saved or edited.';
						} else
							$data['error'] = 'Permissions not set correctly.';
				}
				
			} else {
				$data = [
					'error' => 'Somehing went wrong'
				];
			}
			redirect('users/login');
			// $this->view('pages/addphoto', $data); // error during processing
		} else {
			redirect('users/login'); // not POST
		}
	}
}