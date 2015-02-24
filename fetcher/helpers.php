<?php
$upOne = realpath(__DIR__ . '/..');
include $upOne. DIRECTORY_SEPARATOR .'bootstrap.php';
include realpath(__DIR__) . DIRECTORY_SEPARATOR . 'useragents.php';
use \Httpful\Request as Request;

function make_gamespot_urlname($name) {
    //echo($name);
    $toreplace = array(" - ",". ",".-","@",";","&");
    $replacers = array("-","-","-","-","-","and");
    $game_name = str_replace($toreplace, $replacers, $name);
    $toreplace = array(" ", ":", "'","!",".",",");
    $replacers = array("-","","","","-","-");
    $game_name = str_replace($toreplace, $replacers, $game_name);
    $toreplace = array("--", "---","Idolmaster");
    $replacers = array("-","-","Idolm-ster");
    $game_name = str_replace($toreplace, $replacers, $game_name);
    return $game_name;
}

function get_profile($profile_name){
    $uri = "http://psntrophyleaders.com/user/view/".$profile_name."#games&platform=All&gsort=Progress_desc&gametype=All";
    $k = 1;
    $try = true;
    $profile = array();
    do {
        try {
            $response = Request::get($uri)
                ->addHeader('User-Agent', useragent())
                ->followRedirects(20)
                ->send();
            $try = false;
            $profile['code'] = $response->code;
            if($profile['code'] == 200){
                $profile['body'] = $response->body;
            }
        } catch (\Httpful\Exception\ConnectionErrorException $e) {
            sleep(5);
            $try = ++$k < 6;
        }
    } while($try);

    return $profile;
}

function get_game($game_name){
    $uri = "http://www.gamespot.com/{$game_name}/";

    //echo $uri;

    $k = 1;
    $try = true;
    $gamedata = array();
    //sleep(4);
    do {
        try {
            $response = Request::get($uri)
                ->addHeader('User-Agent', useragent())
                ->followRedirects(20)
                ->send();
            $try = false;
            $gamedata['code'] = $response->code;
            if($gamedata['code'] == 200){
                $gamedata['body'] = $response->body;
            }
        } catch (\Httpful\Exception\ConnectionErrorException $e) {
            sleep(5);
            $try = ++$k < 6;
        }
    } while($try);

    return $gamedata;

}

function get_gamedata_gamespot($html) {
    $doc = phpQuery::newDocumentHTML($html);
    $array = [
        'name' => pq('a.wiki-title)',$doc)->text(),
        'genre' => pq('a[href*="genre"]',$doc)->text(),
        'url'=> pq('img[itemprop="image"]',$doc)->attr('src'),
    ];
    return $array;
}

function get_profiledata_psnprofiles($html){
    $doc = phpQuery::newDocumentHTML($html);
    $array = [
        'average_completion' => pq('span[title*="average completion"]',$doc)->text(),

    ];
}


?>
