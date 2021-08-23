<?php

namespace App\src\constraint;

class Constraint
{
    public function notBlank($name, $value)
    {
        if(empty($value)) {
            return '<div class="invalid-feedback">Le champ '.$name.' saisi est vide</div>';
        }
    }
    public function minLength($name, $value, $minSize)
    {
        if(strlen($value) < $minSize) {
            return '<div class="invalid-feedback">Le champ '.$name.' doit contenir au moins '.$minSize.' caractères</div>';
        }
    }
    public function maxLength($name, $value, $maxSize)
    {
        if(strlen($value) > $maxSize) {
            return '<div class="invalid-feedback">Le champ '.$name.' doit contenir au maximum '.$maxSize.' caractères</div>';
        }
    }
}