<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/4/15
 * Time: 8:17 AM
 */

namespace Controllers;
use Documents;
use Views;
use Games;
use Doctrine\ODM\MongoDB\DocumentManager;
use Controllers\QueryController;


class NewUserViewController {
    private $user;
    private $view;
    private $game;

    public function __construct(Documents\User $user, Views\NewUserView $view) {
        $this->user = $user;
        $this->view = $view;
        $this->game = new Documents\Game();
    }

    public function generateView (DocumentManager $dm){
        $query = new QueryController($dm);
        $games = $query->giveDistinctValues($this->game, 'name');
        $k = rand(0,sizeof($games));
        $game = $query->findOneItem($this->game,'name',$games[$k]);
        $results['pic'] = $this->game->getPic();
        $results['name'] = $this->game->getName();


        return $this->view->giveIl($results);
    }



} 