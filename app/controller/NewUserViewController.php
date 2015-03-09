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
use Doctrine\ODM\MongoDB\DocumentManager;
use Controllers\QueryController;


class NewUserViewController {
    private $user;
    private $view;
    private $game;

    public function __construct(Documents\User $user, Views\UserView $view) {
        $this->user = $user;
        $this->view = $view;
    }

    public function generateView (DocumentManager $dm){
        $query = new QueryController($dm);
        $game = new Documents\Game();
        $games = $query->giveDistinctValues($game, 'name');
        $k = rand(0,sizeof($games));
        $gq = $query->findOneItem($game,'name',$games[$k]);
        $game = $query->findById($gq['_id'], $game);
        $results['pic'] = $game->getPic();
        $results['name'] = $game->getName();
        $results['pane'] = "pane1";
        //var_dump($results);


        return $this->view->giveIl($results);
    }



} 