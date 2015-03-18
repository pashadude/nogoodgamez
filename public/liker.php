<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/17/15
 * Time: 1:26 PM
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
require_once (ROOT . DS . 'bootstrap.php');


use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\User;
use Documents\Game;
use Views\UserView;
use Documents\Assessment;
use Controllers\QueryController;
use Controllers\UserViewController;


$input=$_POST;
$connection = new Connection();


$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('nogoodgames');
$config->setMetadataDriverImpl(AnnotationDriver::create(ROOT . DS .'app/model'));

AnnotationDriver::registerAnnotationClasses();

//$current_ssid = session_id();
$current_ssid = "k1b6387tqka91rk6u3dii6ve20";
$dm = DocumentManager::create($connection, $config);
$user = new User();
$game = new Game();
$view = new UserView();
$query = new QueryController($dm);

$game_query = $query->findOneItem($game,'name','Dead Space 2'/*$input['gamename']*/);
$game = $query->findById($game_query['_id'], $game);
//var_dump($game);

$user_query = $query->findOneItem($user,'session',$current_ssid);
$user = $query->findById($user_query['_id'], $user);
//var_dump($user_query);

$assmnt = new Assessment();
$cont = new UserViewController($user, $view, $game, $assmnt);

if($input['action'] == 'like') {
    $cont->processLike($dm, TRUE);
} elseif ($input['action'] == 'dislike'){
    $cont->processLike($dm, FALSE);
}

$pane = "pane".$input['pane'];
echo $cont->generateExistingUserView($dm,$pane);


?>






