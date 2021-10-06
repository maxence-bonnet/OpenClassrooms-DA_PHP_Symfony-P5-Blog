<?php

namespace App\Src\constraint;

use App\Config\Parameter;

class Validation
{
    protected $constraint;
    protected $requiredFields;
    protected $errors = [];

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function validate($data, $name)
    {
        if ($name === 'Article') {
            $articleValidation = new ArticleValidation();
            $errors = $articleValidation->check($data);
            return $errors;
        } elseif ($name === 'Comment') {
            $commentValidation = new CommentValidation();
            $errors = $commentValidation->check($data);
            return $errors;
        } elseif ($name === 'User') {
            $userValidation = new UserValidation();
            $errors = $userValidation->check($data);
            return $errors;
        } elseif ($name === 'ContactForm') {
            $contactFormValidation = new ContactFormValidation();
            $errors = $contactFormValidation->check($data);
            return $errors;
        }
    }

    protected function check(Parameter $post)
    {
        foreach ($post->all() as $key => $value) {
            $this->checkField($key, $value);
            $this->updateRequiredFields($key);
        }
        // check if all required fields has been found & cleared from array
        if (!empty($this->requiredFields)) {
            $this->addError('missingField',1);
        }
        return $this->errors;
    }

    protected function updateRequiredFields($key) : void
    {
        if (in_array($key,$this->requiredFields)) {
            unset($this->requiredFields[array_search($key,$this->requiredFields)]);
        }
    }

    protected function addError($name, $error) 
    {
        if ($error) {
            $this->errors += [
                $name => '<div class="invalid-feedback">' . $error . '</div>'
            ];
        }
    }
}