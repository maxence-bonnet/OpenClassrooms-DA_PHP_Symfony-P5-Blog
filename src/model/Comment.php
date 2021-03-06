<?php

namespace App\Src\Model;

class Comment extends Model
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $lastModified;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $userPseudo;    

    /**
     * @var int
     */
    private $articleId;

    /**
     * @var int
     */
    private $articleTitle;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $validated;    

    /**
     * @var int
     */
    private $answerTo;


    public function getExcerptContent()
    {
        if ($this->content === null) {
            return null;
        }
        return $this->getExcerpt($this->content, 20);
    }


    /**
     * @return int
     */
    public function getId(){return $this->id;}

    /**
     * @return \DateTime
     */
    public function getCreatedAt(){return $this->createdAt;}

    /**
     * @return \DateTime
     */
    public function getLastModified(){return $this->lastModified;}

    /**
     * @return int
     */
    public function getUserId(){return $this->userId;}

    /**
     * @return string
     */
    public function getUserPseudo(){return $this->userPseudo;}

    /**
     * @return int
     */
    public function getArticleId(){return $this->articleId;}

    /**
     * @return string
     */
    public function getArticleTitle(){return $this->articleTitle;}

    /**
     * @return string
     */
    public function getContent(){return $this->content;}

    /**
     * @return int
     */
    public function getValidated(){return $this->validated;}

    /**
     * @return int
     */
    public function getAnswerTo(){return $this->answerTo;}


    /**
     * @param int $id
     */
    public function setId($id){$this->id = $id;}

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt){$this->createdAt = $createdAt;}

    /**
     * @param \DateTime $lastModified
     */
    public function setLastModified($lastModified){$this->lastModified = $lastModified;}

    /**
     * @param int $userId
     */
    public function setUserId($userId){$this->userId = $userId;}

    /**
     * @param string $user
     */
    public function setUserPseudo($userPseudo)
    {
        $this->userPseudo = 'Utilisateur';
        if ($userPseudo) {
            $this->userPseudo = $userPseudo;
        } 
    }

    /**
     * @param int $articleId
     */
    public function setArticleId($articleId){$this->articleId = $articleId;}

    /**
     * @param string $article
     */
    public function setArticleTitle($articleTitle){$this->articleTitle = $articleTitle;}

    /**
     * @param string $content
     */
    public function setContent($content){$this->content = $content;}

    /**
     * @param int $validated
     */
    public function setValidated($validated){$this->validated = $validated;}

    /**
     * @param int $answerTo
     */
    public function setAnswerTo($answerTo){$this->answerTo = $answerTo;}
}