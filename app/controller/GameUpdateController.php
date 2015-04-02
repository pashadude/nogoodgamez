<?php
namespace Controllers;
use Documents;


class GameUpdateController{
    private $game;

    public function __construct(Documents\Game $game) {
        $this->game = $game;
    }

    public function updateGame($values) {
        $this->game->setName($values['name']);
        $this->game->setPic($values['picurl']);

        foreach ($values['platforms'] as $platform) {
            $this->game->addPlatform($platform);
        }

        foreach ($values['genres'] as $genre) {
            $this->game->addGenre($genre);
        }
    }
}



?>