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
use Controllers\AssessmentUpdateController;



class UserViewController {
    private $user;
    private $view;
    private $game;
    private $assmnt;

    public function __construct(Documents\User $user, Views\UserView $view, Documents\Game $game, Documents\Assessment $assmnt) {
        $this->user = $user;
        $this->view = $view;
        $this->game = $game;
        $this->assmnt = $assmnt;
    }

    public function processLike(DocumentManager $dm, $like){
        $liker = new AssessmentUpdateController($this->user->getId() ,$this->game->getName() ,$this->assmnt);
        $liker->updateAssessment($like, $dm);
        $dm->persist($this->assmnt);
        $dm->flush();
        // enter prediction-io update 0HTssOzKaJu7WTkj - token
    }

    public function generateNewUserView(DocumentManager $dm, $pane = 'pane1'){
        $query = new QueryController($dm);
        $games = $query->giveDistinctValues($this->game, 'name');
        $k = rand(0,sizeof($games));
        $gq = $query->findOneItem($this->game,'name',$games[$k]);
        $game = $query->findById($gq['_id'], $this->game);
        $results['pic'] = $game->getPic();
        $results['name'] = $game->getName();
        $results['pane'] = $pane;
        return $this->view->giveIl($results);
    }

    public function generateExistingUserView(DocumentManager $dm, $pane){
        $query = new QueryController($dm);
        //run prediction.io function which gets $game_name, one of arguments should be $this->user
        $games = $query->giveDistinctValues($this->game, 'name');
        $k = rand(0,sizeof($games));
        $gq = $query->findOneItem($this->game,'name',$games[$k]);
        $game = $query->findById($gq['_id'], $this->game);
        $results['pic'] = $game->getPic();
        $results['name'] = $game->getName();
        $results['pane'] = $pane;
        return $this->view->giveIl($results);
    }

} 