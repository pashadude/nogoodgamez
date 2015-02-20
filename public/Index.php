<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once (ROOT . DS . 'bootstrap.php');


use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\Game;
use Controllers\GameUpdateController;
use Controllers\QueryController;





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

$game = new Game();
$gamedata = array();

$csv = new parseCSV();
$csv1 = new parseCSV();

$csv->auto(ROOT.DS.'data/psn_gamez.csv');
$csv1->auto(ROOT.DS.'data/gmz.csv');

$k = 0;

foreach ($csv->data as $gamelist_data){
   $gamedata['name'] = $gamelist_data['Game'];
   if (strlen($gamelist_data['Platforms']) == 8) {
       $gamedata['platforms'][0] = 'PS 3';
       $gamedata['platforms'][1] = 'PS 4';
   } elseif ($gamelist_data['Platforms'] == 'PS3') {
       $gamedata['platforms'][0] = 'PS 3';
   } else {
       $gamedata['platforms'][0] = 'PS 4';
   }
   $tech_name = make_urlname($gamedata['name']);
   foreach($csv1->data as $games_custom) {
       if($tech_name == $games_custom['url_rules']){
           $tech_name = $games_custom['url_custom'];
           break;
       }
   }
   if($tech_name != "game_dismissed"){
       echo $gamelist_data['Game'];
       print_r(get_game($tech_name));
   }
   //echo "<br>";
   $k++;
   if($k == 10) {break;}
}






//test game input
/*
$gamedata['name'] = 'Grand Theft Auto V';
$gamedata['picurl'] = 'http://static1.gamespot.com/uploads/scale_tiny/469/4693985/2330894-2322534-2227554-grand%2Btheft%2Bauto%2Bv.jpg';
$gamedata['genres'][0] = '3D';
$gamedata['genres'][1] = 'First-Person';
$gamedata['genres'][2] = 'Third-Person';
$gamedata['genres'][3] = 'Shooter';
$gamedata['genres'][4] = 'Adventure';
$gamedata['genres'][5] = 'Open-World';
$gamedata['genres'][6] = 'Action';
$gamedata['platforms'][0] = 'PS 3';
$gamedata['platforms'][1] = 'PS 4';


$finder = new QueryController($dm);

$query = $finder->findOneItem($game,'name',$gamedata['name']);
//var_dump ($query);

if ($query === NULL) {
    $updater = new GameUpdateController($game);
    $updater->updateGame($gamedata);
    $dm->persist($game);
    $dm->flush();
} else {
    echo "dat gaim alreedy eksists, nigga";
}
*/



//query via builder
/*
$user = $dm->createQueryBuilder(get_class($game))
    ->field('name')->equals($game_name)
    ->hydrate(false)
    ->getQuery()
    ->getSingleResult();*/


//simple finder query
//$user = $dm->getRepository('Documents\Game')->findBy(array('name' => 'GTA'));

//巴莎


?>