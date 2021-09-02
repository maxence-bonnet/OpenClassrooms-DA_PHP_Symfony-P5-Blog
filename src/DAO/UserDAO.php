<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\config\Session;
use App\src\model\User;


class UserDAO extends DAO
{
    private function buildObject($row)
    {
        $user = new User(); 
        $user->setId($row['id']);
        $user->setScore($row['createdAt']);
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

    public function pseudoExists($pseudo)
    {
        $sql = 'SELECT COUNT(pseudo) FROM user WHERE pseudo = :pseudo';
        $result = $this->createQuery($sql, [':pseudo' => htmlentities($pseudo)]);
        return $result->fetchColumn();;
    }

    public function register(Parameter $post)
    {
        $sql = 'INSERT INTO user (created_at, firstname, lastname, pseudo, password, mail, phone, role_id, status, score)
                VALUES(NOW(), :firstname, :lastname, :pseudo, :password, :mail, :phone, :role_id, :status, :score)';
        $this->createQuery($sql, [
            'firstname' => $post->get('firstname'),
            'lastname' => $post->get('lastname'),
            'pseudo' => $post->get('pseudo'),
            'password' => password_hash($post->get('password'),PASSWORD_BCRYPT),
            'mail' => $post->get('mail'),
            'phone' => $post->get('phone'),
            'role_id' => 2,
            'status' => 0,
            'score' => 0
        ]);
    }

    public function login(Parameter $post)
    {
        $sql = 'SELECT user.id, user.pseudo, user.password, role.name
                FROM user
                INNER JOIN role ON user.role_id = role_id
                WHERE user.pseudo = :pseudo';
        $data = $this->createQuery($sql, ['pseudo' => $post->get('pseudo')]);
        $result = $data->fetch();
        $passwordValid = password_verify($post->get('password'), $result['password']);
        if($passwordValid){
            $sql = 'UPDATE user 
                    SET status = 1 
                    WHERE pseudo = :pseudo';
            $this->createQuery($sql, ['pseudo' => $post->get('pseudo')]);
        }
        return [
            'result' => $result,
            'passwordValid' => $passwordValid
        ];
    }

    public function logout(int $userId) : void
    {
        $sql ='UPDATE user
               SET status = 0
               WHERE id = :id';
        $this->createQuery($sql, ['id' => $userId]);
    }

    /**
     * EN TRAVAUX
     */
}