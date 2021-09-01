<?php

namespace App\src\constraint;

use App\config\Parameter;

class UserValidation extends Validation
{
    private $password1 = null;
    private $password2 = null;

    public function getPassword1(){return $this->password1;}
    public function getPassword2(){return $this->password2;}

    public function setPassword1($password1){$this->password1 = $password1;}
    public function setPassword2($password2){$this->password2 = $password2;}
    
    public function checkField($name, $value)
    {
        if($name === 'firstname') {
            $error = $this->checkLastname($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'lastname') {
            $error = $this->checkFirstname($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'pseudo') {
            $error = $this->checkPseudo($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'pseudoExists') {
            $error = $this->checkPseudoExists($value);
            $this->addError($name, $error);
        } elseif ($name === 'password') {
            $this->setPassword1($value);
            $error = $this->checkPassword($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'password2') {
            $this->setPassword2($value);
            $error = $this->checkPassword2($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'mail') {
            $error = $this->checkMail($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'phone') {
            $error = $this->checkPhone($name, $value);
            $this->addError($name, $error);
        }
        if(!is_null($this->getPassword1()) && !is_null($this->getPassword2())){
            $name = 'password2';
            $error = $this->checkSamePasswords($this->getPassword1(), $this->getPassword2());
            $this->addError($name, $error);
            $this->setPassword1(null);
            $this->setPassword2(null);
        }
    }

    public function checkLastname($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Nom', $value);
        }
        if($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('Nom', $value, 2);
        }
        if($this->constraint->maxLength($name, $value, 60)) {
            return $this->constraint->maxLength('Nom', $value, 60);
        }
    }

    public function checkFirstname($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Prénom', $value);
        }
        if($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('Prénom', $value, 2);
        }
        if($this->constraint->maxLength($name, $value, 60)) {
            return $this->constraint->maxLength('Prénom', $value, 60);
        }
    }

    public function checkPseudo($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Pseudo', $value);
        }
        if($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('Pseudo', $value, 2);
        }
        if($this->constraint->maxLength($name, $value, 60)) {
            return $this->constraint->maxLength('Pseudo', $value, 60);
        }
    }

    public function checkPseudoExists($value)
    {
        if($this->constraint->pseudoExists($value)) {
            return $this->constraint->pseudoExists($value);
        }
    }

    public function checkPassword($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Mot de passe', $value);
        }
        if($this->constraint->minLength($name, $value, 8)) {
            return $this->constraint->minLength('Mot de passe', $value, 8);
        }
        if($this->constraint->maxLength($name, $value, 120)) {
            return $this->constraint->maxLength('Mot de passe', $value, 120);
        }
        if($this->constraint->PassRequirements($name, $value)) {
            return $this->constraint->PassRequirements('Mot de passe', $value);
        }
    }

    public function checkPassword2($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Confirmation de mot de passe', $value);
        }   
    }

    public function checkSamePasswords ($password1, $password2)
    {
        if($this->constraint->checkSamePasswords($password1, $password2)) {
            return $this->constraint->checkSamePasswords($password1, $password2);
        }   
    }

    public function checkMail($name, $value)
    {
        if($this->constraint->isMail($name, $value)) {
            return $this->constraint->notBlank('Mail', $value);
        }
    }

    public function checkPhone($name, $value)
    {
        if(!empty($value) && $this->constraint->minLength($name, $value, 10)) {
            return $this->constraint->minLength('Téléphone', $value, 10);
        }
        if($this->constraint->maxLength($name, $value, 10)) {
            return $this->constraint->maxLength('Téléphone', $value, 10);
        }
    }
}