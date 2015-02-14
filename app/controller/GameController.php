<?php

class gameController extends BaseController
{

    private $pic;
    private $genres = array();

    public function pushData ($pic, $genres){
        $this->model->setPic($pic);
        foreach ($genres as $genre) {
            $this->model->addGenre($genre);
        }
    }

}


?>