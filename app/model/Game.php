<?php
namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */

class Game
{	
	/** @ODM\Id */
    private $id;
 
    /** @ODM\String */
    private $pic;

    /** @ODM\String */
    private $platform;

    /** @ODM\String */
    private $name;

    /** @ODM\Collection*/
    private $genres = array();

    public function addGenre($genre)
    {
    	$this->genres[] = $genre;
    }


    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPic($uri)
    {
        $this->pic = $uri;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPic()
    {
        return $this->pic;
    }

    public function getGenres()
    {
        return $this->genres;
    }



  
}



?>