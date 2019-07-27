<?php

// this class is used to load models and views
class Controller {

	public function model($model){
		// returns the requested model from models folder
		require_once '../app/models/' . $model . '.php';
		return (new $model());
	}

	public function view($view, $data = []){
        if (file_exists('../app/views/' . $view . '.php')){
			require_once '../app/views/' . $view . '.php';
		} else {
			echo 'Error : view not found!' . '<br>';
		}
	}
}