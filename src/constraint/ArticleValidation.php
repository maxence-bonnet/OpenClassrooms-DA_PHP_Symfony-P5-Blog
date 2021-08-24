<?php

namespace App\src\constraint;

use App\config\Parameter;

class ArticleValidation extends Validation
{   
    public function checkField($name, $value)
    {
        if($name === 'title') {
            $error = $this->checkTitle($name, $value);
            $this->addError($name, $error);
        }
        elseif ($name === 'lede') {
            $error = $this->checkLede($name, $value);
            $this->addError($name, $error);
        }
        elseif ($name === 'content') {
            $error = $this->checkContent($name, $value);
            $this->addError($name, $error);
        }
    }

    public function checkTitle($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Titre', $value);
        }
        if($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('titre', $value, 2);
        }
        if($this->constraint->maxLength($name, $value, 63)) {
            return $this->constraint->maxLength('titre', $value, 63);
        }
    }

    public function checkLede($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('chapô', $value);
        }
        if($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('chapô', $value, 2);
        }
        if($this->constraint->maxLength($name, $value, 500)) {
            return $this->constraint->maxLength('chapô', $value, 500);
        }
    }

    public function checkContent($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('contenu', $value);
        }
        if($this->constraint->minLength($name, $value, 100)) {
            return $this->constraint->minLength('contenu', $value, 100);
        }
        if($this->constraint->maxLength($name, $value, 2000)) {
            return $this->constraint->maxLength('contenu', $value, 2000);
        }
    }

    /**
     * EN TRAVAUX
     */
}