<?php
include ROOT.DS.'bootstrap.php';
include realpath(__DIR__) .DS. 'useragents.php';

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

function get_leaderboard ($page_name){
    $uri = "http://psntrophyleaders.com/leaderboard/main/".$page_name;
    $k = 1;
    $try = true;
    $page = array();
    do {
        try {
            $response = Request::get($uri)
                ->addHeader('User-Agent', useragent())
                ->followRedirects(20)
                ->send();
            $try = false;
            $page['code'] = $response->code;
            if($page['code'] == 200){
                $page['body'] = $response->body;
            }
        } catch (\Httpful\Exception\ConnectionErrorException $e) {
            sleep(5);
            $try = ++$k < 6;
        }
    } while($try);

    return $page;
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

function get_body ($uri){
    $k = 1;
    $try = true;
    $page = array();
    do {
        try {
            $response = Request::get($uri)
                ->addHeader('User-Agent', useragent())
                ->followRedirects(20)
                ->send();
            $try = false;
            $page['code'] = $response->code;
            if($page['code'] == 200){
                $page['body'] = $response->body;
            }
        } catch (\Httpful\Exception\ConnectionErrorException $e) {
            sleep(5);
            $try = ++$k < 6;
        }
    } while($try);

    return $page['body'];
}

function get_review_pages ($pages){
    $urls = array();
    for($i=1;$i<=$pages;$i++){
        $urls[] = "http://www.gamespot.com/reviews/ps4/?page=".$i;
    }
    return $urls;
}

function get_review_htmls($html){
    $urls =array();
    $doc = phpQuery::newDocumentHTML($html);
    $reviews = $doc->find('a.js-event-tracking');
    foreach ($reviews as $review) {
        $pq = pq($review);
        $urls[] = $pq->attr('href');
    }
    return $urls;

}

function get_gameurl_from_review($html){
    $doc = phpQuery::newDocumentHTML($html);
    $url = pq('li.subnav-list__item.subnav-list__item-primary',$doc)->children("a")->attr('href');
    return "http://www.gamespot.com".$url;
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
        'games' => $doc->find('a.game-title')->text(),
        'platforms'=>pq('.title.sort',$doc)->text(),
        'completions'=> pq('.progress.sort',$doc)->text(),
    ];
    return $array;
}

function fetch_profiledata_psnprofiles($profile_data){
    $results['average'] = intval(str_replace("%","",$profile_data['average_completion']));
    $results['platforms'] = preg_split('/\\r\\n|\\r|\\n/', $profile_data['platforms']);
    $results['games'] = preg_split('/\\r\\n|\\r|\\n/', $profile_data['games']);
    //$count = 0;
    do{
        $profile_data['completions'] = preg_replace('/\\s[0](?=[^-\\s])/', ' 0 ',$profile_data['completions'], -1, $count);
    } while ($count > 0);
    $results['completions'] =  preg_split('/\\r\\n|\\r|\\n|\\s/', $profile_data['completions']);
    $k = count($results['completions']);
    for ($i=0;$i < $k; $i++){
        $results['completions'][$i] = intval( $results['completions'][$i]);
        $results['games'][$i] = preg_replace("/(™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/", "",$results['games'][$i]);
    }
    return ($results);
}

function parse_profiledata_psnfprofiles($values){
   $k = count($values['completions']);
   $j = 0;
   for ($i=0;$i < $k; $i++) {
       //echo strpos($values['platforms'][$i],'-ps4')." ";
       //echo $values['completions'][$i]." ;";
       if(strpos($values['platforms'][$i],'-ps4') != NULL || strpos($values['platforms'][$i],'-ps3') != NULL ){
            if($values['completions'][$i] > $values['average']){
                $games[$j] = $values['games'][$i];
                $j++;
            }
       }
   }
   return $games;
}

function parse_leaderboard($html){
    $doc = phpQuery::newDocumentHTML($html);
    $players = $doc->find('a.user-select');

    foreach ($players as $player) {
        $pq = pq($player);
        $users[] = $pq->text();
    }

    return $users;

}





?>
