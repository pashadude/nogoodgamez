<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once(ROOT . DS . 'bootstrap.php');


use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\Game;
use Controllers\GameUpdateController;
use Controllers\QueryController;

function create_update_unit ($gamespot_gamedata, &$gamedata){
    $gamedata['picurl'] = $gamespot_gamedata['url'];
    $gamedata['name'] = $gamespot_gamedata['name'];
    $genres = preg_split('/\\r\\n|\\r|\\n/', $gamespot_gamedata['genre']);
    $k = 0;
    foreach ($genres as $genre) {
        if ($genre != ""&&(strlen(preg_replace('/\s+/u','',$genre)) != 0)&&(strlen($genre)<=27)){
            $gamedata['genres'][$k] = $genre;
            $k++;
        }
    }
}



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

$gamedata = array();

$csv = new parseCSV();
$csv1 = new parseCSV();

$csv->auto(ROOT.DS.'data/psn_gamez_new.csv');
$csv1->auto(ROOT.DS.'data/gmz.csv');


foreach ($csv->data as $gamelist_data){

   if (strlen($gamelist_data['Platforms']) == 8) {
       $gamedata['platforms'][0] = 'PS 3';
       $gamedata['platforms'][1] = 'PS 4';
   } elseif ($gamelist_data['Platforms'] == 'PS3') {
       $gamedata['platforms'][0] = 'PS 3';
   } else {
       $gamedata['platforms'][0] = 'PS 4';
   }
   $tech_name = make_gamespot_urlname($gamelist_data['Game']);
   foreach($csv1->data as $games_custom) {
       if($tech_name == $games_custom['url_rules']){
           $tech_name = $games_custom['url_custom'];
           break;
       }
   }
   if($tech_name != "game_dismissed"){
       $info = (get_game($tech_name));

       if($info['code'] == 200){
           $gamespot_gamedata = get_gamedata_gamespot($info['body']);
           create_update_unit($gamespot_gamedata, $gamedata);

           $game = new Game();
           $finder = new QueryController($dm);
           $query = $finder->findOneItem($game,'name',$gamedata['name']);

           if ($query === NULL) {
               $updater = new GameUpdateController($game);
               $updater->updateGame($gamedata);
               $dm->persist($game);
               $dm->flush();
//if you would like to save pictures on your server
/*
	       $gq = $finder->findOneItem($game, 'name', $gamedata['name']);
               $gm = $finder->findById($gq['_id'], $game);
               $id = $gm->getId();
               $input = $gm->getPic();
               $ftype = substr($input, -3);
               if($ftype = "peg"){$ftype  = substr($input, -4);}
               $output = '../public/img/games/'.$id.".".$ftype;
               file_put_contents($output, file_get_contents($input));
               $gm->setPic($output);
               $dm->persist($game);
               $dm->flush();
               var_dump($gm);

*/

           } else {
               echo $gamedata['name']."is already in the database";
           }

           $gamespot_gamedata = array();
       } else {
           echo $gamelist_data['Game']." data can not be extracted, code ".$info['code'];
       }

   }

   $gamedata = array();
}






//巴莎


?>
