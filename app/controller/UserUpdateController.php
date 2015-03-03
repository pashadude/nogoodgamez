<?php
/**
 * Created by PhpStorm.
 * User: proveyourskillz
 * Date: 3/3/15
 * Time: 7:17 AM
 */

namespace Controllers;
use Documents;


class UserUpdateController {

    private $user;

    public function __construct(Documents\User $user) {
        $this->user = $user;
    }

    public function updateRealUser($session) {
        $this->user->setReal(TRUE);
        $this->user->setSession($session);
    }

    public function updateInitialUser() {
        $this->user->setReal(FALSE);
    }


} 