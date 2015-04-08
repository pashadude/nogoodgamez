<?php
namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User
{
	/** @ODM\Id */
    private $id;

    /** @ODM\Boolean */
    private $real;

    /** @ODM\String*/
    private $session;

    
    public function setReal($real)
    {
        $this->real = $real;
    }

    public function setSession($session)
    {
         	$this->session = $session;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReal()
    {
        return $this->real;
    }

    public function getSession()
    {
        return $this->session;
    }
}


?>