<?php
namespace Documents;

/** @Document(collection="games") */
class Game
{	
	/** @Id */
    private $id;
 
    /** @String */
    private $pic;

    /** @String */
    private $name;

    /** @Collection*/
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

    public function getId()
    {
        return $this->id;
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