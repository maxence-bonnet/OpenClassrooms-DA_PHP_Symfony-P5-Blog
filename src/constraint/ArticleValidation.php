<?php

namespace App\src\constraint;
use App\config\Parameter;

class ArticleValidation extends Validation
{
    private $errors = [];
    

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function check(Parameter $post)
    {
        foreach ($post->all() as $key => $value) {
            $this->checkField($key, $value);
        }
        return $this->errors;
    }

    private function checkField($name, $value)
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

    private function addError($name, $error) {
        if($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    private function checkTitle($name, $value)
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

    private function checkLede($name, $value)
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

    private function checkContent($name, $value)
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