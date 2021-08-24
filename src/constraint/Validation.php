<?php

namespace App\src\constraint;

use App\config\Parameter;

class Validation
{
    protected $constraint;
    protected $errors = [];

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function validate($data, $name)
    {
        if($name === 'Article') {
            $articleValidation = new ArticleValidation();
            $errors = $articleValidation->check($data);
            return $errors;
        } elseif($name === 'Comment') {
            $commentValidation = new CommentValidation();
            $errors = $commentValidation->check($data);
            return $errors;
        } elseif ($name === 'User') {
            $userValidation = new UserValidation();
            $errors = $userValidation->check($data);
            return $errors;
        }
    }

    protected function check(Parameter $post)
    {
        foreach ($post->all() as $key => $value) {
            $this->checkField($key, $value);
        }
        return $this->errors;
    }

    protected function addError($name, $error) {
        if($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }
}