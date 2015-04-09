<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/25/15
 * Time: 1:02 PM
 */

namespace Controllers;
use predictionio\EventClient;
use predictionio\EngineClient;
use predictionio\PredictionIOAPIError;

class PredictionController {
    private $access_key;
    private $prediction_server;
    private $engine_server;


    public function __construct($access_key, $prediction_server, $engine_server) {
        $this->key = $access_key;
        $this->server = $prediction_server;
        $this->engine = new EngineClient($engine_server);
        $this->client = new EventClient($access_key, $prediction_server);
    }

    public function set_like($user_id, $item_id){
        $result = $this->client->recordUserActionOnItem('like', $user_id, $item_id);
        print_r($result);
    }

    public function set_user($id){
        $result = $this->client->setUser($id);
        print_r($result);
    }

    public function set_item($item_id, $types){
        $result = $this->client->setItem($item_id, array('itypes'=>$types));
        print_r($result);
    }

    public function retrieve_prediction($like_ids, $dislike_ids){
        $response = $this->engine->sendQuery(array(
            'num'=>1,
            'items'=> $like_ids,
            'blackList'=> $dislike_ids
        ));
        /*print_r($this->engine->client);
        try {
            $response = $this->engine->getStatus();
            var_dump($response);
        } catch (PredictionIOAPIError $e) {
 	     echo $e->getMessage();
	}*/
       
        $game_id = $response['itemScores'][0]['item'];
        //$game_id = null;
        return $game_id;
    }

}
