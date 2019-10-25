<?php
class Pages extends Controller {
	private $postModel;

	public function __construct(){
		$this->postModel = $this->model('Post');
	}

	public function index(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
			$offset = 5 * ($_GET['page'] - 1);
			$page_number = $_GET['page'];
		} else {
			$offset = 0;
			$page_number = 1;
		}
		if (empty($posts = $this->postModel->getPostsPaged($offset))){
			$posts = $this->postModel->getPostsPaged(0);
			$page_number = 1;
		}
		if (!empty($_SESSION['user_name']))
			$user_name = $_SESSION['user_name'];
		else
			$user_name = '';

		foreach($posts as $key => $post){
			$comments	= $this->postModel->getComments($post['image_id']); // get comments for this post sorted by creation date ASC.
			$likes		= $this->postModel->getLikes($post['image_id']);
			$posts[$key]['comments'] = $comments;
			$posts[$key]['likes'] = $likes;
		}
		
		$data = [
			'number_of_pages' => $this->postModel->getNumberOfPages(),
			'current_page' => $page_number,
			'title'		=> 'index page',
			'posts'		=> $posts,
			'user_name'	=> $user_name,
		];

		$this->view('pages/index', $data);
	}

	public function like(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'
				&& isLoggedIn() && isset($_POST['image_id']) && isset($_POST['current_user'])){
			echo $this->postModel->likePost($_POST['image_id'], $_POST['current_user']);
		} else {
			redirect('users/login');
		}
	}


	public function comment(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			if (isLoggedIn()){		// added a comment
				$image_id = key($_POST);
				if (!empty($_POST[$image_id]) && $this->postModel->postExists($image_id)){
					$newcomment = filter_var($_POST[$image_id], FILTER_SANITIZE_STRING);
					$newcomment = trim($newcomment);
					if (!empty($newcomment) && $this->postModel->storeComment($image_id, $_SESSION['user_id'], $newcomment)){
						$this->postModel->sendNotification(['image_id' => $image_id, 'user_name' => $_SESSION['user_name']]);
						echo json_encode([1, $_SESSION['user_name'], $newcomment]);
					} else
						echo json_encode([3]);
				} else
					echo json_encode([4]);
			} else {
				echo json_encode([2, URLROOT . '/users/login']);
			}
		} else
			redirect('pages/index');
	}

    public function about(){
		$data = [
			'title' => 'about page',
			'description' => 'This is my php camagru-project web application, it\'s an instagram-like app the allows users to customize and take pictures, share, comment, and like other people\'s posts'
		];
		$this->view('pages/about', $data);
	}

	public function add(){
		if (isLoggedIn()){
			$posts = $this->postModel->getAllUserPosts($_SESSION['user_name']);

			$data = [
				'title' => 'add photo page',
				'posts'		=> $posts,
				'stickers'	=> [
					0 => '../app/photos/superpos/megaman.png',
					1 => '../app/photos/superpos/melon.png ',
					2 => '../app/photos/superpos/rajang.png',
					3 => '../app/photos/superpos/ayano.png',
					4 => '../app/photos/superpos/scumbag.png',
				],
			];
			$this->view('pages/addphoto', $data);
		} else {
			redirect('users/login');
		}
	}

	public function	delete(){
		$image_id_to_delete = key($_POST);
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			if (isLoggedIn() && $this->postModel->isFromUser($image_id_to_delete, $_SESSION['user_id'])){
				if ($this->postModel->deletePost($image_id_to_delete))
					echo 'deleted';	 // redirect('pages/add');
				else
					echo 'Error : image was not deleted<br>';
			} else
				echo '!deleted';	// redirect('pages/add');
		} else 
			echo '!deleted';// redirect('pages/add');
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
			if (key($_POST) == 'image' && isset($_POST['image']) && substr($_POST['image'], 0, 22) === 'data:image/png;base64,'
				&& isset($_POST['name']) && $_POST['name'] > 0 && $_POST['name'] < 6){					// Upload with js
				$sticker = getStickerName($_POST['name']);
				$decodedimg = base64_decode(substr($_POST['image'], 22, strlen($_POST['image'])));
				$ex = substr($decodedimg , 1, 3);
				$destpath = APPROOT . '/photos/posts/' . $unique_name . '.png';
				if (!($size = @filesize($destpath)))
					$size = 0;
				if ($ex == 'PNG' && $size < $maxsize){
					file_put_contents($destpath, $decodedimg);
					$postpic = [
						'user_id'		=> $_SESSION['user_id'],
						'image'			=> '/photos/posts/' . $unique_name . '.png',
						'image_type'	=> 'png',
						'image_size'	=> $size,
					];
					
					if (($retimg = blendImages($sticker, $destpath))
						&& ($returnId = $this->postModel->storePost($postpic))){
						echo json_encode([$returnId, 'data:image/png;base64,'.base64_encode(file_get_contents($destpath))]);
						$data['error'] = '';
					}
				}
			} else if (isset($_FILES['uploadedimage']) && $_FILES['uploadedimage']['error'] === 0
					&& isset($_FILES['uploadedimage']['name']) && isset($_FILES['uploadedimage']['type'])
						&& isset($_FILES['uploadedimage']['size']) && key($_POST) > 0 && key($_POST) < 6){	// Upload with PHP
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
							$unique_name = uniqid(substr($_SESSION['user_name'], 0, 3));
						}
						if (touch($uploads_dir . $unique_name . '.' . $imgext) && isset($_FILES["uploadedimage"]["tmp_name"])
							&& move_uploaded_file($_FILES["uploadedimage"]["tmp_name"], $uploads_dir . $unique_name . '.' . $imgext)){
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
				redirect('pages/add');
			}
		} else {
			redirect('users/login'); // not POST
		}
	}
}