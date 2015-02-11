<?php

include '../bootstrap.php';
include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'useragents.php';

use \Httpful\Request as Request;
$games = $db->games;
$players = $db->players;

public function parse_player($playername)
{
	
}


public function parse_games($game)
{
	
}


# Fetching leaderboard gamertags function
function get_leaderboard_players($uri) {
	global $players;
	$response = Request::get($uri)->parseWith(function($body) {
		return phpQuery::newDocumentHTML($body);
	})->send();
	# Parsing
	foreach (pq('#leaderboard tr.row', $response->body) as $gamertagTr) {
		$players->insert([
			'player'   => pq('td:nth-child(3)', $gamertagTr)->text()
		]);
		//launch player parsing
	}
}


function loop_leaderboards($fromPage, $toPage){
	for ($i=$fromPage; $i <= $toPage; $i++) {
		$k = 1;
		$try = true;
		$result = false;
		$uri = "http://xboxgamertag.com/leaderboards/{$i}";
		$start_time = microtime(true);
		do {
			try {
				get_leaderboard_players($uri);
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
			echo "Page #{$i} ready for {$delta} sec\n";
		} else {
			echo "Page #{$i} can`t be fetched after {$delta} sec\n";
		}
	}
}
