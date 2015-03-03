<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once (ROOT . DS . 'bootstrap.php');


use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\Game;
use Documents\User;
use Documents\Assessment;
use Controllers\GameUpdateController;
use Controllers\QueryController;
use Controllers\AssessmentUpdateController;
use Controllers\UserUpdateController;



$connection = new Connection();

$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('nogoodgames');
$config->setMetadataDriverImpl(AnnotationDriver::create(ROOT . DS .'app/model'));

AnnotationDriver::registerAnnotationClasses();

//full like process

$dm = DocumentManager::create($connection, $config);

$user = new User();

$updater = new UserUpdateController($user);
$updater->updateInitialUser();

$dm->persist($user);
$dm->flush();

//$user_id = $user->getId();
//var_dump($user_id);

$game = new Game();
$game_name = "Madden NFL 15";

$assessment = new Assessment();

$liker = new AssessmentUpdateController($user_id, $game_name, $assessment);
$liker->updateAssessment(1, $dm);

$dm->persist($assessment);
$dm->flush();


//巴莎


?>