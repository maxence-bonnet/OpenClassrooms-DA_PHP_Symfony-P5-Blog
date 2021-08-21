<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\src\model\User;

class UserDAO extends DAO
{
    private function buildObject($row)
    {
        $user = new User(); 
        $user->setId($row['id']);
        $user->setFirstname($row['firstname']);
        $user->setLastname($row['lastname']);  
        $user->setPseudo($row['pseudo']);      
        $user->setMail($row['mail']);
        $user->setPhone($row['phone']);
        $user->setRole($row['role_name']);
        $user->setStatus($row['status']);
        $user->setScore($row['score']);
        return $user;
    }
}