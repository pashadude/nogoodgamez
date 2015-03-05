<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/4/15
 * Time: 8:14 PM
 */

namespace Views;
use Controllers;


class NewUserView {
    public function giveIl($data){

        return '<li class="pane1">
                    <div class="img" style="background: url('.$data['pic'].') no-repeat scroll center center;background-size: cover;"></div>
                    <div>'.$data['name'].'</div>
                    <div class="like"></div>
                    <div class="dislike"></div>
                </li>';
    }
} 