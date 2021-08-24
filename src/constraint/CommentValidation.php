<?php

namespace App\src\constraint;

use App\config\Parameter;

class CommentValidation extends Validation
{
    public function checkField($name, $value)
    {
        if ($name === 'content') {
            $error = $this->checkContent($name, $value);
            $this->addError($name, $error);
        }
    }

    public function checkContent($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('contenu', $value);
        }
        if($this->constraint->minLength($name, $value, 6)) {
            return $this->constraint->minLength('contenu', $value, 6);
        }
        if($this->constraint->maxLength($name, $value, 500)) {
            return $this->constraint->minLength('contenu', $value, 500);
        }
    }
}