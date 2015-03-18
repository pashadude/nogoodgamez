<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/17/15
 * Time: 1:26 PM
 */

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\Game;
use Documents\User;
use Views\UserView;
use Documents\Assessment;
use Controllers\GameUpdateController;
use Controllers\QueryController;
use Controllers\AssessmentUpdateController;
use Controllers\UserUpdateController;
use Controllers\UserViewController;



$connection = new Connection();
$current_ssid = session_id();

$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('nogoodgames');
$config->setMetadataDriverImpl(AnnotationDriver::create(ROOT . DS .'app/model'));

$current_ssid = session_id();
$input=$_POST;


if($input['action'] == 'like') {
    $dm = DocumentManager::create($connection, $config);
    $user = new User();
    $game = new Game();
    $view = new UserView();
    $query = new QueryController($dm);

    $game_query = $query->findOneItem($game,'name',$input['gamename']);
    $game = $query->findById($game_query['_id'], $game);

    $user_query = $query->findOneItem($user,'session',$current_ssid);
    $user = $query->findById($user_query['_id'], $user);

    $assmnt = new Assessment();

    $cont = new UserViewController($user, $view, $game, $assmnt);
    $cont->processLike($dm, TRUE);
    $pane = "pane".$input['pane'];
    echo $cont->generateExistingUserView($dm,$pane);
}

?>