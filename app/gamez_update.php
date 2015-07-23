<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once(ROOT . DS . 'bootstrap.php');

use Documents\Game;
use Controllers\GameUpdateController;
use Controllers\QueryController;



$fetcher = new Fetcher();
$results = $fetcher->getGameIgdb(1979);
//$results = $fetcher->getPlatformGames('Playstation 4');
var_dump($results);

/*
$results = array();
$urls = get_review_pages(1);
foreach ($urls as $url) {
    $review_pages = get_review_htmls(get_body($url));
    foreach($review_pages as $review){
        $html = get_gameurl_from_review(get_body("http://www.gamespot.com".$review));
        $results[] = get_gamedata_gamespot(get_body($html));
    }
}*/








//巴莎


?>