<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/4/15
 * Time: 8:14 PM
 */

namespace Views;
use Controllers;


class UserView {
    public function giveIl($data){
    //update font
        $html = '<li class="%3$s">
                     <div class="img" style="background: url(\'%1$s\') no-repeat scroll center; background-size: cover;"></div>
                     <div id="game%3$s" style="font: 0.7em/0.9em helvetica,arial,sans-serif;">%2$s</div>
                     <div class="like"></div>
                     <div class="dislike"></div>
                 </li>';
       // print_r($html);
       return sprintf($html, $data['pic'], $data['name'], $data['pane']);
    }
} 