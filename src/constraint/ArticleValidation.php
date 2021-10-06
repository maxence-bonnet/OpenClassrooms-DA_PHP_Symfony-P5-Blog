<?php

namespace App\Src\Constraint;

use App\Config\Parameter;

class ArticleValidation extends Validation
{   
    public function __construct()
    {
        parent::__construct();
        $this->requiredFields = ['title','categoryId','authorId','lede','content','statusId'];
    }

    public function checkField($name, $value)
    {
        if ($name === 'title') {
            $error = $this->checkTitle($name, $value);
            $this->addError($name, $error);
        } elseif ($name === 'authorId') {
            $error = $this->checkAuthorId($value);
            $this->addError($name, $error);
        } elseif ($name === 'categoryId') {
            $error = $this->checkCategoryId($value);
            $this->addError($name, $error);
        } elseif ($name === 'lede') {
            $error = $this->checkLede($name, strip_tags($value));
            $this->addError($name, $error);
        } elseif ($name === 'content') {
            $error = $this->checkContent($name, strip_tags($value));
            $this->addError($name, $error);
        } elseif ($name === 'statusId') {
            $error = $this->checkStatusId($name, $value);
            $this->addError($name, $error);
        }
    }

    private function checkTitle($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Titre', $value);
        }
        if ($this->constraint->minLength($name, $value, 3)) {
            return $this->constraint->minLength('titre', $value, 3);
        }
        if ($this->constraint->maxLength($name, $value, 63)) {
            return $this->constraint->maxLength('titre', $value, 63);
        }
    }

    private function checkLede($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('chapô', $value);
        }
        if ($this->constraint->minLength($name, $value, 50)) {
            return $this->constraint->minLength('chapô', $value, 50);
        }
        if ($this->constraint->maxLength($name, $value, 500)) {
            return $this->constraint->maxLength('chapô', $value, 500);
        }
    }

    private function checkContent($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('contenu', $value);
        }
        if ($this->constraint->minLength($name, $value, 100)) {
            return $this->constraint->minLength('contenu', $value, 100);
        }
        if ($this->constraint->maxLength($name, $value, 5000)) {
            return $this->constraint->maxLength('contenu', $value, 5000);
        }
    }

    private function checkStatusId($name, $value)
    {
        $valuesArray = [1,2,3];
        if ($this->constraint->inArray($name, (int)$value, $valuesArray)) {
            return $this->constraint->inArray('Statut de publication', (int)$value, $valuesArray);
        }
    }

    private function checkAuthorId($value)
    {
        if ($this->constraint->existingUserId($value)) {
            return $this->constraint->existingUserId($value);
        }
    }

    private function checkCategoryId($value)
    {
        if ($this->constraint->existingCategoryId($value)) {
            return $this->constraint->existingCategoryId($value);
        }        
    }
}