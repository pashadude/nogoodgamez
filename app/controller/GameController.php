<?php

class GameController extends BaseController

{	
	public function addGenres ($genres)
	{
		foreach ($genres as $genre) {
			$this->model->addGenre($genre);
		}

	}

	
}

?>