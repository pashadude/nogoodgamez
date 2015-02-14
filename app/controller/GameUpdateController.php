<?php
namespace Controller;
use Documents\Game;

class GameUpdateController{
    private $game;

    public function __construct(Game $game) {
        $this->game = $game;
    }

    public function findGame($dm, $game_name){
        return $dm->getRepository('Game')->findOneBy(array('name' => $game_name));
    }

    public function updateGame($values) {
        $this->game->setName($values['name']);
        $this->game->setPic($values['picurl']);
        foreach ($values['genres'] as $genre) {
                  $this->game->addGenre($genre);
        }
    }
}



?>