<?php

namespace App\src\constraint;

use App\config\Parameter;

class ContactFormValidation extends Validation
{
    public function checkField($name, $value)
    {
        if($name === 'firstname') {
            $error = $this->checkLastname($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'lastname') {
            $error = $this->checkFirstname($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'mail') {
            $error = $this->checkMail($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'message') {
            $error = $this->checkMessage($name, $value);
            $this->addError($name, $error);
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

    public function checkMessage($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Message', $value);
        }
        if($this->constraint->minLength($name, $value, 20)) {
            return $this->constraint->minLength('Message', $value, 20);
        }
        if($this->constraint->maxLength($name, $value, 500)) {
            return $this->constraint->minLength('Message', $value, 500);
        }
    }

    public function checkMail($name, $value)
    {
        if($this->constraint->isMail($name, $value)) {
            return $this->constraint->isMail('Mail', $value);
        }
    }
}