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
use Documents\Assessment;
use Controllers\GameUpdateController;
use Controllers\QueryController;
use Controllers\AssessmentUpdateController;
use Controllers\UserUpdateController;

$csv = new parseCSV();
$csv->auto(ROOT.DS.'data/games_not_in_list.csv');

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

$page_start = 801;
$page_finished = 803;
$games_in_list = array();
$games_not_in_list = array();


for ($i = $page_start; $i <= $page_finished; $i++ ) {
    $leaderboard = get_leaderboard($i);
    if ($leaderboard['code'] != 200) {
        echo 'leaderboard page number '.$i.' has not been fetched with code '.$leaderboard['code'];
    } else {
        $users = parse_leaderboard($leaderboard['body']);
        foreach ($users as $user) {
            $request = get_profile($user);
            if ($request['code'] != 200){
                echo 'player ' .$user.' has not been fetched with code '.$request['code'];
            } else {
                $player = new User();

                $updater = new UserUpdateController($player);
                $updater->updateInitialUser();

                $dm->persist($player);
                $dm->flush();

                $user_id = $player->getId();

                $data = get_profiledata_psnprofiles($request['body']);
                $results = fetch_profiledata_psnprofiles($data);
                $games_to_like = parse_profiledata_psnfprofiles($results);
                foreach ($games_to_like as $game_like) {

                    foreach($csv->data as $games_custom) {
                        if($game_like == $games_custom['psnprofiles']){
                            $game_like = $games_custom['matching'];
                            break;
                        }
                    }

                    $game = new Game();
                    $assessment = new Assessment();

                    $liker = new AssessmentUpdateController($user_id, $game_like, $assessment);
                    $liker->updateAssessment(1, $dm);

                    $dm->persist($assessment);
                    $dm->flush();

                }

            }

        }
    }

}

//var_dump ($games_in_list);
//print_r($games_not_in_list);



//巴莎


?>