<?php

class Core {
	protected $currentController = 'Pages';//default method has to have index method
	protected $currentMethod = 'index';
	protected $params = [];

	public function __construct(){
		$url = $this->getUrl();
		//Call the appropriate Controller if it exists
		if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
			$this->currentController = ucwords($url[0]);
			unset($url[0]);
		}
		require_once '../app/controllers/' . $this->currentController . '.php'; // if the previous if-conditions fails, require Default controller, otherwise use the requested one
		$this->currentController = new $this->currentController; // Instantiate the class

		if (isset($url[1])){
			//Call the appropriate Method if it exists
			if (method_exists($this->currentController, $url[1])){
				$this->currentMethod = $url[1];
				unset($url[1]);
			}
		}
		$this->params = $url ? array_values($url) : []; // pass the rest as parameters if they exist (controller and method are unset).
		call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
	}

	public function getUrl(){
		if (isset($_GET['url'])){
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);
			return($url);
		}
	}
}