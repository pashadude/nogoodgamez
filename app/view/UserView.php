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

        $html = '<li class="%3$s">
                     <div class="img" style="background: url(\'%1$s\') no-repeat scroll center; background-size: cover;"></div>
                     <div>%2$s</div>
                     <div class="like"></div>
                     <div class="dislike"></div>
                 </li>';
       return sprintf($html, $data['pic'], $data['name'], $data['pane']);
    }
} 