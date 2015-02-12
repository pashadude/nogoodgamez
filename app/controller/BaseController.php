<?php


class BaseController
{	
	private $model;


	
	public function __construct($model) {
		$this->model = $model;
	}

	
}

?>