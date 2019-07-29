<?php


class Pages extends Controller {
	private $postModel;

	public function __construct(){
		$this->postModel = $this->model('Post');
	}

	public function index(){
		$posts = $this->postModel->getPosts();
		
		$data = [
			'title' => 'index page',
			'posts' => $posts,
		];

		$this->view('pages/index', $data);
	}

    public function about(){
		$data = [
			'title' => 'about page',
			'description' => 'This is my php camagru-project web application, it\'s an instagram-like app the allows users to customize and take pictures, share, comment, and like other people\'s posts'
		];
		$this->view('pages/about', $data);
	}
}