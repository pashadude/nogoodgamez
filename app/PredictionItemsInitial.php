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
    'localhost:7070',
    'localhost:8000');



foreach ($gamez as $gamename) {
     $game = new Game();
     $gamegenres = array();
     $gq = $query->findOneItem($game, 'name', $gamename);
     $gm = $query->findById($gq['_id'], $game);
     $id = $gm->getId();
     $genres = $gm->getGenres();
     $prophet->set_item($id, $genres);

     $input = $gm->getPic();
     $ftype = substr($input, -3);
     if($ftype = "peg"){$ftype  = substr($input, -4);}
     $output = '../public/img/games/'.$id.".".$ftype;
     file_put_contents($output, file_get_contents($input));
     $gm->setPic($output);
     $dm->persist($game);
     $dm->flush();
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
            //var_dump($a['game']['$id']->{'$id'});
            $item_id = $a['game']['$id']->{'$id'};
            //echo($item_id.":".$id);
            //var_dump ($id);
            $prophet->set_like($id, $item_id);
        }
    }
}



?>