<?php

namespace App\src\constraint;

use App\config\Parameter;

class CommentValidation extends Validation
{
    public function __construct()
    {
        parent::__construct();
        $this->requiredFields = ['comment','answer'];
    }
    
    public function checkField($name, $value)
    {
        if ($name === 'comment' || $name === 'answer') {
            $error = $this->checkComment($name, $value);
            $this->addError($name, $error);
            // comment can be either 'answer' or 'comment', need to clear both if one is received
            if($name === 'comment'){
                unset($this->requiredFields[array_search('answer',$this->requiredFields)]);
            }
            unset($this->requiredFields[array_search('comment',$this->requiredFields)]);
        }
    }

    public function checkComment($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Contenu', $value);
        }
        if($this->constraint->minLength($name, $value, 6)) {
            return $this->constraint->minLength('Contenu', $value, 6);
        }
        if($this->constraint->maxLength($name, $value, 500)) {
            return $this->constraint->minLength('Contenu', $value, 500);
        }
    }
}