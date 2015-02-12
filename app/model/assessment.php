<?php
namespace Documents;

/** @Document(collection="assessments") @HasLifecycleCallbacks*/
class Assessment
{
	/** @Id */
    private $id;

    /** @ReferenceOne(targetDocument="Documents\Game") */
    private $game;

    /** @ReferenceOne(targetDocument="Documents\User") */
    private $user;

    /** @Boolean */
    private $like;

    /** @Date */
    private $happened;

    public function setLike($like)
    {
        $this->like = $like;
    }

    public function setHappened($happened)
    {
        $this->happened = $happened;
    }

    public function addGame ($game_name){
        $game = $dm->getRepository('Game')->findOneBy(array('name' => $game_name));
        $this->game = $game;
    }

    public function addUser ($user_name){
    	$game = $dm->getRepository('User')->findOneBy(array('name' => $user_name));
        $this->user = $user;
    }

    public function setGame(Game $game) { $this->game = $game; }
    
    public function setUser(User $user) { $this->user = $user; }


    public function getId()
    {
        return $this->id;
    }

    public function getLike()
    {
        return $this->like;
    }

    public function getHappened()
    {
        return $this->happened;
    }




    

}

?>