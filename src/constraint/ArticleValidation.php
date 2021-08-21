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
            return $this->constraint->notBlank('Title', $value);
        }
        if($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('title', $value, 2);
        }
        if($this->constraint->maxLength($name, $value, 63)) {
            return $this->constraint->maxLength('title', $value, 63);
        }
    }

    private function checkLede($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('lede', $value);
        }
        if($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('lede', $value, 2);
        }
        if($this->constraint->maxLength($name, $value, 500)) {
            return $this->constraint->maxLength('lede', $value, 500);
        }
    }

    private function checkContent($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('content', $value);
        }
        if($this->constraint->minLength($name, $value, 100)) {
            return $this->constraint->minLength('content', $value, 100);
        }
        if($this->constraint->maxLength($name, $value, 2000)) {
            return $this->constraint->maxLength('lede', $value, 2000);
        }
    }

    /**
     * EN TRAVAUX
     */
}