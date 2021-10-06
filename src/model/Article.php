<?php

namespace App\Src\Model;

use App\Src\Utils\Text;

class Article extends Model
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
     * @var string
     */
    private $title;

    /**
     * Teaser / intro
     * 
     * @var string
     */
    private $lede;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $authorId;

    /**
     * @var string
     */
    private $authorPseudo;

    /**
     * @var int
     */
    private $categoryId;    

    /**
     * @var string
     */
    private $categoryName;    

    /**
     * @var int
     */
    private $statusId;

    /**
     * @var string
     */
    private $statusName;

    /**
     * @var int
     */
    private $allowComment;


    public function getLedeExcerpt() : ?string
    {
        if ($this->lede === null) {
            return null;
        }
        $content = Text::removeMarkdown($this->lede);
        return $this->getExcerpt($content, 120);
    }


    /**
     * @return int
     */
    public function getId() {return $this->id;}

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {return $this->createdAt;}

    /**
     * @return \DateTime
     */
    public function getLastModified() {return $this->lastModified;}

    /**
     * @return string
     */
    public function getTitle() {return $this->title;}

    /**
     * @return string
     */
    public function getLede() {return $this->lede;}

    /**
     * @return string
     */
    public function getContent() {return $this->content;}

    /**
     * @return int
     */
    public function getAuthorId() {return $this->authorId;}

    /**
     * @return string
     */
    public function getAuthorPseudo() {return $this->authorPseudo;}

    /**
     * @return int
     */
    public function getCategoryId() {return $this->categoryId;}

    /**
     * @return string
     */
    public function getCategoryName() {return $this->categoryName;}

    /**
     * @return int
     */
    public function getStatusId() {return $this->statusId;}

    /**
     * @return string
     */
    public function getStatusName() {return $this->statusName;}

    /**
     * @return int
     */
    public function getAllowComment() {return $this->allowComment;}


    /**
     * @param int $id
     */
    public function setId($id) {$this->id = $id;}

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt) {$this->createdAt = $createdAt;}

    /**
     * @param \DateTime $lastModified
     */
    public function setLastModified($lastModified) {$this->lastModified = $lastModified;}

    /**
     * @param string $title
     */
    public function setTitle($title) {$this->title = $title;}

    /**
     * @param string $lede
     */
    public function setLede($lede) {$this->lede = $lede;}

    /**
     * @param string $content
     */
    public function setContent($content) {$this->content = $content;}

    /**
     * @param string $authorId
     */
    public function setAuthorId($authorId) {$this->authorId = $authorId;}

    /**
     * @param string $authorPseudo
     */
    public function setAuthorPseudo($authorPseudo) {$this->authorPseudo = $authorPseudo;}

    /**
     * @param string $categoryId
     */
    public function setCategoryId($categoryId) {$this->categoryId = $categoryId;}

    /**
     * @param string $category
     */
    public function setCategoryName($categoryName) {$this->categoryName = $categoryName;}

    /**
     * @param int $statusId
     */
    public function setStatusId($statusId) {$this->statusId = $statusId;}

    /**
     * @param string $statusName
     */
    public function setStatusName($statusName) {$this->statusName = $statusName;}

    /**
     * @param int $allowComment
     */
    public function setAllowComment($allowComment) {$this->allowComment = $allowComment;}
}