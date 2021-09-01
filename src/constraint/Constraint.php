<?php

namespace App\src\constraint;

class Constraint
{
    const EXISTING_PSEUDO = 'AAAAA';

    public function notBlank($name, $value)
    {
        if(empty($value)) {
            return '<div class="invalid-feedback">Le champ '.$name.' saisi est vide.</div>';
        }
    }

    public function minLength($name, $value, $minSize)
    {
        if(strlen($value) < $minSize) {
            return '<div class="invalid-feedback">Le champ '.$name.' doit contenir au moins '.$minSize.' caractères.</div>';
        }
    }

    public function maxLength($name, $value, $maxSize)
    {
        if(strlen($value) > $maxSize) {
            return '<div class="invalid-feedback">Le champ '.$name.' doit contenir au maximum '.$maxSize.' caractères.</div>';
        }
    }

    public function pseudoExists($value)
    {
        if($value === self::EXISTING_PSEUDO){
            return '<div class="invalid-feedback">Ce pseudonyme est déjà utilisé, veuillez en choisir un autre.</div>';
        }
    }

    public function PassRequirements($name, $value)
    {
        if(!preg_match("#(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\d)(?=.*[^A-Za-z\d])#",$value)){
            return '<div class="invalid-feedback">Le champ '.$name.' ne respecte pas le format demandé.</div>';
        }
    }

    public function checkSamePasswords($password1,$password2)
    {
        if($password1 !== $password2) {
            return '<div class="invalid-feedback">Les mots de passe ne correspondent pas.</div>';
        }
    }

    public function isMail($name, $value)
    {
        if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
            return '<div class="invalid-feedback">Le champ '.$name.' est invalide.</div>';
        }
    }
}