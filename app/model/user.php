<?php
namespace Documents;

/** @Document(collection="users") */
class User
{
	/** @Id */
    private $id;

    /** @Boolean */
    private $real;

    /** @String*/
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