<?php
class UpdateController{
    private $names = array();

    public function __construct($names) {
        $this->names = $names;
    }

    public function updateGames() {
        foreach ($this->names as $name) {
            $game = new Game();
            $game->setName($name['name']);
            $controller = new GameController($game);
            $controller->pushData($name['pic'],$name['genres']);
        }
    }
}

?>