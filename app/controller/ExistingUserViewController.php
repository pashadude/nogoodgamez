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

class ExistingUserViewController {
    private $user;
    private $view;
    private $game;

    public function __construct(Documents\User $user, Views\UserView $view, Documents\Game $game, Documents\Assessment $assessment) {
        $this->user = $user;
        $this->view = $view;
        $this->game = $game;
        $this->assmnt = $assessment;
    }

    public function updateUser (DocumentManager $dm, $like) {
        $liker = new AssessmentUpdateController($this->user->getId() ,$this->game->getName() ,$this->assmnt);
        $liker->updateAssessment($like, $dm);
        $dm->persist($this->assmnt);
        $dm->flush();
        //update prediction.io with $this->user
    }

    public function generateView (DocumentManager $dm, $pane){
        $query = new QueryController($dm);
        //run prediction.io function which gets $game_name, one of arguments should be $this->user
        $gq = $query->findOneItem($this->game,'name',$game_name);
        $game = $query->findById($gq['_id'], $this->game);
        $results['pic'] = $game->getPic();
        $results['name'] = $game->getName();
        $results['pane'] = $pane;
        return $this->view->giveIl($results);
    }

} 