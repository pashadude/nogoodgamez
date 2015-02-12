<?php
# Boostrapping
include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'useragents.php';
define('GAMERCARDS', realpath(__DIR__) . DIRECTORY_SEPARATOR . 'gamercards' . DIRECTORY_SEPARATOR);

use \Httpful\Request as Request;
$games = $db->games;
$gamesCollection = $db->games;
$gamesCursor = $gamesCollection->find(['$or' => [['html_saved' => false], ['html_saved' => null]]]);

foreach ($gamesCursor as $objectId => $gameDoc) {
	$k = 1;
	$try = true;
	$result = false;
	$game_name=str_replace(" ", "-", $gameDoc['game']);//change to the format of gamespot
	$uri = "http://gamespot.com/{$game_name}/summary/";
	$start_time = microtime(true);
	do {
		try {
			$response = Request::get($uri)
				->addHeader('User-Agent', useragent())
				->send();
			file_put_contents(GAMERCARDS . $objectId . '.html', $response->body);
			$gamesCollection->update(['_id' => new MongoId($objectId)], ['$set' => ['html_saved' => true]]);
			$try = false;
			$result = true;
		} catch (\Httpful\Exception\ConnectionErrorException $e) {
			sleep(5);
			$try = ++$k < 6;
		}
	} while($try);
	$end_time = microtime(true);
	$delta = round($end_time - $start_time, 4);
	if ($result) {
		echo "{$gameDoc['game']} ready for {$delta} sec\n";
	} else {
		echo "{$gameDoc['game']} can`t be fetched after {$delta} sec\n";
	}
}
