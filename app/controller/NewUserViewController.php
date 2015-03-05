<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/4/15
 * Time: 8:17 AM
 */

namespace Controllers;
use Documents;
use Views;



class NewUserViewController {
    private $user;
    private $view;

    public function __construct(Documents\User $user, Views\NewUserView $view) {
        $this->user = $user;
        $this->view = $view;
    }

    public function generateView ($slides = 3){
        $game = new Documents\Game () ;


    }



} 