<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once(ROOT . DS . 'bootstrap.php');

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\Game;
use Documents\User;
use Controllers\QueryController;
use Controllers\PredictionController;

/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/28/15
 * Time: 1:47 AM
 */

$connection = new Connection();

$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('nogoodgames');
$config->setMetadataDriverImpl(AnnotationDriver::create(ROOT . DS .'app/model'));

AnnotationDriver::registerAnnotationClasses();

$dm = DocumentManager::create($connection, $config);


$query = new QueryController($dm);

$game = new Game();
$gamez = $query->giveDistinctValues($game, 'name');
//var_dump($gamez);

$prophet = new PredictionController('B72Iu4Nn4IisHoHOFEPgOh2sWvYSMaJ05B7I5E1Gq120qg3AaIJ8hwdmBapToBTm',
    'http://localhost:7070',
    'http://localhost:8000');


foreach ($gamez as $gamename) {
     $gamegenres = array();
     $gq = $query->findOneItem($game, 'name', $gamename);
     $gm = $query->findById($gq['_id'], $game);
     $id = $gm->getId();
     $genres = $gm->getGenres();
     $prophet->set_item($id, $genres);
     //var_dump($id);
     //var_dump($genres);
}

$user = new User();
$users = $query->giveDistinctValues($user, '_id');
//var_dump($users);
$assmnt = new \Documents\Assessment();

/*
$exmpl1 = $query->findOneItem($user,'_id','54f6e5c9ec59cfc9178b544d');
var_dump($exmpl1['_id']);
$exmpl = $query->findById($exmpl1['_id'],$user);
var_dump($exmpl);*/


foreach ($users as $userid){
    $id = $userid->{'$id'};
    //var_dump ($id);
    $prophet->set_user($id);
    $aq = $query->findAllItems($assmnt,'user.$id',$userid);
    foreach ($aq as $a){
        if($a['like'] == true){
            $item_id = $a['_id']->{'$id'};
            //var_dump($item_id);
            $prophet->set_like($id, $item_id);
        }
    }
}



?>