<?php

namespace App\src\model;

class User
{
    /**
     * @var int        
     */
    private $id;       

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
     * @var string
     */
    private $role;

    /**
     * @var boolean
     */
    private $status;

    /**
     * @var int
     */
    private $score;


    /**
     * @return int
     */
    public function getId(){return $this->id;}

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
     * @return string
     */
    public function getRole(){return $this->role;}

    /**
     * @return boolean
     */
    public function getStatus(){return $this->status;}

    /**
     * @return int
     */
    public function getScore(){return $this->score;}

    
    /**
     * @param int id
     */
    public function setId($id){$this->id = $id;}

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
     * @param string role
     */
    public function setRole($role){$this->role = $role;}

    /**
     * @param boolean status
     */
    public function setStatus($status){$this->status = $status;}

    /**
     * @param int score
     */
    public function setScore($score){$this->score = $score;}
}