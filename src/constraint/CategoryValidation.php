<?php

namespace App\src\constraint;
use App\config\Parameter;

class CategoryValidation extends Validation
{
    private $errors = [];
    private $constraint;
}