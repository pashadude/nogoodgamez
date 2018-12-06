<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 6/28/15
 * Time: 12:26 PM
 */
include ROOT.DS.'bootstrap.php';
//include realpath(__DIR__) .DS. 'useragents.php';



use \Httpful\Request as Request;


class Fetcher
{
    public static $igdbPlatforms = [
        'Playstation 3' => 9,
        'Playstation 4' => 48,
        'Xbox 360' => 12,
        'Xbox One' => 49,
    ];

    private function igdbRequest($url)
    {
        $k = 1;
        $try = true;
        $page = array();
        do {
            try {
                $response = Request::get($url)
                    ->addHeaders(array(
                        'Accept' => 'application/json',
                        'Authorization' => 'Token token=""'
                    ))
                    ->followRedirects(20)
                    ->send();
                $try = false;
                $page['code'] = $response->code;
                if ($page['code'] == 200) {
                    $page['body'] = $response->body;
                }
            } catch (\Httpful\Exception\ConnectionErrorException $e) {
                sleep(5);
                $try = ++$k < 6;
            }
        } while ($try);

        return $page['body'];
    }

    public function getGame($game_id)
    {
        $url = 'http://igdb.com/api/v1/games/' . $game_id;
        return $this->igdbRequest($url);
    }

    public function getPlatformGames($platform)
    {
        $url = 'http://igdb.com/api/v1/games/search?filters[platforms_eq]='.self::$igdbPlatforms[$platform];
        echo $url;
        return $this->igdbRequest($url);
    }
}


?>
