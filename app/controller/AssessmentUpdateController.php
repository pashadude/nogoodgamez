<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/3/15
 * Time: 2:42 PM
 */

namespace Controllers;

use Documents;
use Doctrine\ODM\MongoDB\DocumentManager;
use Controllers\QueryController;

class AssessmentUpdateController {
    public function __construct($user_id, $game_name, Documents\Assessment $assessment) {
        $this->user_id = $user_id;
        $this->game_name = $game_name;
        $this->assessment = $assessment;
    }

    public function updateAssessment($like,DocumentManager $dm) {
        $finder = new QueryController($dm);

        $user = new Documents\User();
        $game = new Documents\Game();

        $game_querry = $finder->findOneItem($game,'name',$this->game_name);
        $user = $finder->findById($this->user_id, $user);

        if($game_querry === NULL) {
            return "no such game ".$this->game_name;
        } elseif($user === NULL){
            return "no such user ".$this->user_id;
        }

        $game = $finder->findById($game_querry['_id'], $game);

        //var_dump($game_querry);
        //var_dump($user_querry);

        $this->assessment->setGame($game);
        $this->assessment->setUser($user);
        $this->assessment->setLike($like);
        $this->assessment->setHappened(date('m/d/Y h:i:s a', time()));

    }


} 