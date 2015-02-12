<?php
# Boostrapping
include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

use \Httpful\Request as Request;

# CLI detect
if (php_sapi_name() !== 'cli') {
	throw new Exception("Work only in console!", 1);
}

$gamertagsCollection = $db->gamertags;
$gamertagsCursor = $gamertagsCollection->find(['$or' => [['checked' => false], ['checked' => null]]]);

if (count($argv) > 1) {
	$limit = (int) $argv[1];
	$gamertagsCursor->limit($limit);
}

function get_gamercard($uri = null) {
	$doc = phpQuery::newDocumentFileHTML('samples/gamertag_Stallion83.html');
	$array = [
		'gamerscore' => (int) str_replace(',', '', trim(pq('#topLeft .rightGS', $doc)->text())),
		'last_seen' => pq('#topLeft p.topc:first', $doc)->text(),
		'info' => explode('||', pq('#XGTMain div.lowerPart p', $doc)->text()),
		'last_games' => explode("\n", trim(pq('#XGTMain .rightColGT .topRC div p', $doc)->text())),
	];
	foreach (pq('#recentGamesTable tr') as $tr) {
		$array['games'][] = [
			'name' => pq('td:eq(1) a', $tr)->text(),
			'last_played' => pq('td:eq(1) p:eq(1)', $tr)->text(),
			'progress' => explode('/', pq('td:eq(2) > div:eq(0) div', $tr)->text()),
			'achievements' => explode('/', pq('td:eq(2) > div:eq(1) div', $tr)->text()),
		];
	}
}

get_gamercard();

// foreach ($gamertagsCursor as $objectId => $gamertagDoc) {

// 	$update = ['$set' => ['checked' => true]];
// 	$gamertagsCollection->update(['_id' => new MongoId($objectId)], $update);
// }
