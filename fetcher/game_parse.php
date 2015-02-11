<?php


function get_gamedata($uri = null) {
	$doc = phpQuery::newDocumentFileHTML('samples/gamespot_gta_5.html');
	$array = [
		'genre' => pq( 'a:contains("' . 'Genre'. '")',$doc)->text,
	];
	print_r($array);
}

get_gamedata();

