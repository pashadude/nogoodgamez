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
        //var_dump ($this->user->getId());
        //var_dump ($this->game->getName());
        $liker = new AssessmentUpdateController($this->user->getId() ,$this->game->getName() ,$this->assmnt);
        $liker->updateAssessment($like, $dm);
        $dm->persist($this->assmnt);
        $dm->flush();
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

    public function generateExistingUserView(DocumentManager $dm, $pane, $session_id){
        $query = new QueryController($dm);
        $likes = array();
        $dislikes = array();
        $q_support = $query->findOneItem($this->user,'session',$session_id);
        //var_dump($q_support);
        $user_id_obj = $q_support['_id'];
        //var_dump($user_id_obj);
        //$assmntz = $query->giveDistinctValues($this->assmnt, 'user');
        //var_dump ($assmntz);
        $aq = $query->findAllItems( $this->assmnt, 'user.$id', $user_id_obj);
        //var_dump($aq);
        foreach($aq as $a){
            $item_id = $a['game']['$id']->{'$id'};
            if($a['like'] == true){
                $likes[] = $item_id;
            } else {
                $dislikes[] = $item_id;
            }
        }
        //var_dump($likes);
        if ($likes!=array()){
            $gameid = $this->prophet->retrieve_prediction($likes, $dislikes);
        } else {
            $games = $query->giveDistinctValues($this->game, 'name');
            $k = rand(0,sizeof($games));
            $gq = $query->findOneItem($this->game,'name',$games[$k]);
            $gameid = $gq['_id'];
        }


        $game = $query->findById($gameid, $this->game);
        $results['pic'] = $game->getPic();
        $results['name'] = $game->getName();
        $results['pane'] = $pane;
        return $this->view->giveIl($results);
    }

} 
