<?php

namespace App\Src\constraint;

use App\Src\DAO\UserDAO;
use App\Src\DAO\CategoryDAO;

class Constraint
{
    public function notBlank($name, $value)
    {
        $value = preg_replace("#([\t\n\r\s])#","$2",$value);
        if (empty($value)) {
            return 'Le champ '.$name.' saisi est vide.';
        }
    }

    public function minLength($name, $value, $minSize)
    {
        if (strlen($value) < $minSize) {
            return 'Le champ '.$name.' doit contenir au moins '.$minSize.' caractères.';
        }
    }

    public function maxLength($name, $value, $maxSize)
    {
        if (strlen($value) > $maxSize) {
            return 'Le champ '.$name.' doit contenir au maximum '.$maxSize.' caractères.';
        }
    }
    
    public function passRequirements($name, $value)
    {
        if (!preg_match("#(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\d)(?=.*[^A-Za-z\d])#",$value)) {
            return 'Le champ '.$name.' ne respecte pas le format demandé.';
        }
    }

    public function checkSamePasswords($password1,$password2)
    {
        if ($password1 !== $password2) {
            return 'Les mots de passe ne correspondent pas.';
        }
    }

    public function isEmail($name, $value)
    {
        if (!filter_var($value,FILTER_VALIDATE_EMAIL)) {
            return 'Le champ '.$name.' est invalide.';
        }
    }

    public function inArray($name, $value, $array)
    {
        if (!in_array($value, $array)) {
            return 'Le champ '.$name.' n\'est pas correctement saisi.';
        }
    }

    public function existingPseudo($value)
    {
        if ((new UserDAO())->pseudoExists($value)) {
            return 'Le pseudo : ' . $value . ' est déjà utilisé, veuillez en choisir un autre..';
        }
    }

    public function existingUserId(int $value)
    {
        if (!(new UserDAO())->getUser($value)) {
            return 'L\'utilisateur saisi n\'existe pas.';
        }
    }

    public function existingCategoryId(int $value)
    {
        if (!(new CategoryDAO())->getCategory($value)) {
            return 'La catégorie saisie n\'existe pas.';
        }       
    }
}
