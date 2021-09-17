<?php

namespace App\src\model;

class User extends Model
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
     * @var string     
     */
    private $firstname;

    /**
     * @var string     
     */
    private $lastname;

    /**
     * @var string
     */
    private $pseudo;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var int
     */
    private $roleId;

    /**
     * @var string
     */
    private $roleName;

    /**
     * @var int
     */
    private $statusId;

    /**
     * @var string
     */
    private $StatusName;

    /**
     * @var int
     */
    private $score;


    /**
     * @return int
     */
    public function getId(){return $this->id;}

    /**
     * @return \DateTime
     */
    public function getCreatedAt(){return $this->createdAt;}

    /**
     * @return string
     */
    public function getFirstname(){return $this->firstname;}

    /**
     * @return string
     */
    public function getLastname(){return $this->lastname;}

    /**
     * @return string
     */
    public function getPseudo(){return $this->pseudo;}

    /**
     * @return string
     */
    public function getMail(){return $this->mail;}

    /**
     * @return string
     */
    public function getPhone(){return $this->phone;}

    /**
     * @return int
     */
    public function getRoleId(){return $this->roleId;}

    /**
     * @return string
     */
    public function getRoleName(){return $this->roleName;}

    /**
     * @return int
     */
    public function getStatusId(){return $this->statusId;}

    /**
     * @return string
     */
    public function getStatusName(){return $this->statusName;}

    /**
     * @return int
     */
    public function getScore(){return $this->score;}

    
    /**
     * @param int id
     */
    public function setId($id){$this->id = $id;}

    /**
     * @param \DateTime createdAt
     */
    public function setCreatedAt($createdAt){$this->createdAt = $createdAt;}

    /**
     * @param string firstname
     */
    public function setFirstname($firstname){$this->firstname = $firstname;}

    /**
     * @param string lastname
     */
    public function setLastname($lastname){$this->lastname = $lastname;}

    /**
     * @param string pseudo
     */
    public function setPseudo($pseudo){$this->pseudo = $pseudo;}

    /**
     * @param string mail
     */
    public function setMail($mail){$this->mail = $mail;}

    /**
     * @param string phone
     */
    public function setPhone($phone){$this->phone = $phone;}

    /**
     * @param int roleId
     */
    public function setRoleId($roleId){$this->roleId = $roleId;}

    /**
     * @param string roleName
     */
    public function setRoleName($roleName){$this->roleName = $roleName;}

    /**
     * @param int statusId
     */
    public function setStatusId($statusId){$this->statusId = $statusId;}

    /**
     * @param string statusName
     */
    public function setStatusName($statusName){$this->statusName = $statusName;}

    /**
     * @param int score
     */
    public function setScore($score){$this->score = $score;}
}