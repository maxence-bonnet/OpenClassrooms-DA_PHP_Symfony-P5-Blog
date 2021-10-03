<?php

namespace App\src\model;

class Reaction
{
    /**
     * @var int
     */
    private $articleId;

    /**
     * @var int
     */
    private $userId;
    
    /**
     * @var string
     */
    private $userPseudo;    

    /**
     * @var string
     */
    private $name;

    /**
     * @var int/null
     */
    private $commentId;


    /**
     * @return int
     */
    public function getArticleId(){return $this->articleId;}

    /**
     * @return int
     */
    public function getUserId(){return $this->userId;}

    /**
     * @return string
     */
    public function getUserPseudo(){return $this->userPseudo;}

    /**
     * @return string
     */
    public function getName(){return $this->name;}

    /**
     * @return int/null
     */
    public function getCommentId(){return $this->commentId;}

 
    /**
     * @param int
     */
    public function setArticleId($articleId){$this->articleId = $articleId;}

    /**
     * @param int
     */
    public function setUserId($userId){$this->userId = $userId;}

    /**
     * @param string
     */
    public function setUserPseudo($userPseudo){$this->userPseudo = $userPseudo;}

    /**
     * @param string
     */
    public function setName($name){$this->name = $name;}

    /**
     * @param int/null
     */
    public function setCommentId($commentId){$this->commentId = $commentId;}
}