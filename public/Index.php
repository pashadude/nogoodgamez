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
use Documents\View;
use Documents\Assessment;
use Controllers\GameUpdateController;
use Controllers\QueryController;
use Controllers\AssessmentUpdateController;
use Controllers\UserUpdateController;
use Controllers\NewUserViewController;



$connection = new Connection();

$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('nogoodgames');
$config->setMetadataDriverImpl(AnnotationDriver::create(ROOT . DS .'app/model'));

AnnotationDriver::registerAnnotationClasses();

session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<link rel="stylesheet" type="text/css" href="../app/view/css/jTinder.css">
<head>
    <title>NoGoodGamez</title>
</head>

<body>
<!-- start padding container -->
<div class="wrap">
    <!-- start jtinder container -->
    <div id="tinderslide">
        <ul>
<?php


$current_ssid = session_id();

$dm = DocumentManager::create($connection, $config);
$user = new User();
$query = new QueryController($dm);
$responce = $query->findOneItem($user,'session',$current_ssid);

if($responce === NULL){
    $updater = new UserUpdateController($user);
    $updater->updateRealUser($current_ssid);
    $dm->persist($user);
    $dm->flush();

    $view = new \Views\NewUserView();
    $cont = new NewUserViewController($user, $view);
    echo $cont->generateView($dm);
    //launch view with random game
} else {
    $user = $query->findById($responce['_id'], $user);

    //launch view with games recommended by prediction io
}
?>
</ul>
        </div>
        <!-- end jtinder container -->
    </div>
    <!-- end padding container -->

    <!-- jTinder trigger by buttons  -->
    <div class="actions">
        <a href="#" class="dislike"><i></i></a>
        <a href="#" class="like"><i></i></a>
    </div>

    <!-- jTinder status text  -->
    <div id="status"></div>

    <!-- jQuery lib -->
    <script type="text/javascript" src="../app/view/js/jquery.min.js"></script>
    <!-- transform2d lib -->
    <script type="text/javascript" src="../app/view/js/jquery.transform2d.js"></script>
    <!-- jTinder lib -->
    <script type="text/javascript" src="../app/view/js/jquery.jTinder.js"></script>
    <!-- jTinder initialization script -->
    <script type="text/javascript" src="../app/view/js/main.js"></script>
</body>
</html>

//full like process
/*
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
*/




//巴莎


?>