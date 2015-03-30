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

/*$prophet = new PredictionController('j4jIdbq59JsF2f4CXwwkIiVHNFnyNvWXqMqXxcIbQDqFRz5K0fe9e3QfqjKwvW3O"',
    'http://localhost:7070',
    'http://localhost:8000');*/


foreach ($gamez as $gamename) {
     $gamegenres = array();
     $gq = $query->findOneItem($game, 'name', $gamename);
     $gm = $query->findById($gq['_id'], $game);
     $id = $gm->getId();
     $genres = $gm->getGenres();
     //$prophet->set_item($id, $genres);
     var_dump($id);
     var_dump($genres);
}

$user = new User();
$users = $query->giveDistinctValues($user, '_id');

foreach ($users as $userid){
    $id = $userid->{'$id'};
    var_dump ($id);
   //$prophet->set_user($id);
}



?>