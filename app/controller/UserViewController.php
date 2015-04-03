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
    private $prophet;


    public function __construct(Documents\User $user, Views\UserView $view, Documents\Game $game, Documents\Assessment $assmnt, PredictionController $prophet) {
        $this->user = $user;
        $this->view = $view;
        $this->game = $game;
        $this->assmnt = $assmnt;
        $this->prophet = $prophet;

    }

    public function processLike(DocumentManager $dm, $like){
        $liker = new AssessmentUpdateController($this->user->getId() ,$this->game->getName() ,$this->assmnt);
        $liker->updateAssessment($like, $dm);
        $dm->persist($this->assmnt);
        $dm->flush();


        $id = $this->user->getId();
        $item_id =$this->game->getId();
        //$this->prophet->set_like($id, $item_id);
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


        $likes = array();
        $dislikes = array();
        $user_id = $this->user->getId();
        $q_support = $query->findOneItem($this->user,'_id',$user_id);
        $user_id_obj = $q_support['_id'];
        $aq = $query->findAllItems( $this->assmnt, 'user.$id', $user_id_obj);
        foreach($aq as $a){
            $item_id = $a['game']['$id']->{'$id'};
            if($a['like'] == true){
                $likes[] = $item_id;
            } else {
                $dislikes[] = $item_id;
            }
        }
        $gameid = $this->prophet->retrieve_prediction($likes, $dislikes);


/*
        $games = $query->giveDistinctValues($this->game, 'name');
        $k = rand(0,sizeof($games));
        $gq = $query->findOneItem($this->game,'name',$games[$k]);
        $gameid = $gq['_id'];*/


        $game = $query->findById($gameid, $this->game);
        $results['pic'] = $game->getPic();
        $results['name'] = $game->getName();
        $results['pane'] = $pane;
        return $this->view->giveIl($results);
    }

} 