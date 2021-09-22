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
        $user->setCreatedAt($row['created_at']);
        $user->setFirstname($row['firstname']);
        $user->setLastname($row['lastname']);  
        $user->setPseudo($row['pseudo']);      
        $user->setMail($row['mail']);
        $user->setPhone($row['phone']);
        $user->setRoleId($row['role_id']);
        $user->setRoleName($row['role_name']);
        $user->setStatusId($row['status_id']);
        $user->setStatusName($row['status_name']);
        $user->setScore($row['score']);
        return $user;
    }

    public function getUser(int $userId)
    {
        $sql = 'SELECT user.id, user.created_at, user.firstname, user.lastname, user.pseudo, user.mail, user.phone, user.score, user.status_id, user.role_id,
        user_status.name as status_name,
        user_role.name as role_name
        FROM user
        INNER JOIN user_role ON user.role_id = user_role.id
        INNER JOIN user_status ON user.status_id = user_status.id
        WHERE user.id = :user_id';

        $result = $this->createQuery($sql, [':user_id' => $userId]);
        $user = $result->fetch();
        $result->closeCursor();
        if($user){
            $user = $this->buildObject($user);
        }
        return $user;
    }

    public function updateUserRole(int $userId, int $roleId) : void
    {
        $sql = 'UPDATE user SET role_id = :role_id WHERE id = :user_id';
        $this->createQuery($sql,[
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
    }

    public function updateUserStatus(int $userId, int $statusId) : void
    {
        $sql = 'UPDATE user SET status_id = :status_id WHERE id = :user_id';
        $this->createQuery($sql,[
            'user_id' => $userId,
            'status_id' => $statusId
        ]);
    }


    public function pseudoExists(string $pseudo) : ?int
    {
        $sql = 'SELECT COUNT(pseudo) FROM user WHERE pseudo = :pseudo';
        $result = $this->createQuery($sql, [':pseudo' => htmlentities($pseudo)]);
        return $result->fetchColumn();;
    }

    public function register(Parameter $post) : void
    {
        $sql = 'INSERT INTO user (created_at, firstname, lastname, pseudo, password, mail, phone, role_id, status_id, score)
                VALUES(NOW(), :firstname, :lastname, :pseudo, :password, :mail, :phone, :role_id, :status_id, :score)';
        $this->createQuery($sql, [
            'firstname' => $post->get('firstname'),
            'lastname' => $post->get('lastname'),
            'pseudo' => $post->get('pseudo'),
            'password' => password_hash($post->get('password'),PASSWORD_BCRYPT),
            'mail' => $post->get('mail'),
            'phone' => $post->get('phone'),
            'role_id' => 2,
            'status_id' => 1,
            'score' => 0
        ]);
    }

    public function login(Parameter $post) : array
    {
        $sql = 'SELECT user.id, user.pseudo, user.password, user_status.name as status_name, user_role.name as role_name
                FROM user
                INNER JOIN user_role ON user.role_id = user_role.id
                INNER JOIN user_status ON user.status_id = user_status.id
                WHERE user.pseudo = :pseudo';
        $data = $this->createQuery($sql, ['pseudo' => $post->get('pseudo')]);
        $result = $data->fetch();
        $passwordValid = password_verify($post->get('password'), $result['password']);
        if($passwordValid && $result['status_name'] !== "banned"){
            $sql = 'UPDATE user 
                    SET status_id = 2 
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
               SET status_id = 1
               WHERE id = :id
               AND status_id != 3' ;
        $this->createQuery($sql, ['id' => $userId]);
    }

    public function countUsers(array $parameters = []) : int
    {
        $where = "WHERE";

        extract($parameters);

        $sql = 'SELECT COUNT(user.id) FROM user 
                INNER JOIN user_role ON user.role_id = user_role.id
                INNER JOIN user_status ON user.status_id = user_status.id';
        
        if(isset($q)){
            $sql .= ' ' . $where . ' user.pseudo LIKE "%' . $q . '%"';
            $where = "AND";
        }

        if(isset($scoreHigherThan)){
            $sql .= ' ' . $where . ' user.score > "' . $scoreHigherThan . '"' ;
            $where = "AND";
        }

        if(isset($scoreLowerThan)){
            $sql .= ' ' . $where . ' user.score < "' . $scoreLowerThan . '"';
        }

        if(!isset($allUserStatus)){
            if(isset($online) || isset($offline) || isset($banned)){
                $or = "";
                $sql .= ' ' . $where . ' (' ;
                if(isset($online)){
                    $sql .= ' user_status.name = "online"';
                    $or = " OR ";
                }
                if(isset($offline)){
                    $sql .= $or . ' user_status.name = "offline"';
                    $or = " OR ";
                }
                if(isset($banned)){
                    $sql .= $or . ' user_status.name = "banned"';
                }
                $sql .= ')';
                $where = "AND";
            }
        }

        if(!isset($allUserRoles)){
            if(isset($admin) || isset($moderator) || isset($editor) || isset($user)){
                $or = "";
                $sql .= ' ' . $where . ' (' ;
                if(isset($admin)){
                    $sql .= ' user_role.name = "admin"';
                    $or = " OR ";
                }
                if(isset($moderator)){
                    $sql .= $or . ' user_role.name = "moderator"';
                    $or = " OR ";
                }
                if(isset($editor)){
                    $sql .= $or . ' user_role.name = "editor"';
                    $or = " OR ";
                }
                if(isset($user)){
                    $sql .= $or . ' user_role.name = "user"';
                }
                $sql .= ')';
                $where = "AND";
            }
        }

        if(isset($beforeDatetime)){
            $sql .= ' ' . $where . ' user.created_at < "' . $beforeDatetime . '"' ;
            $where = "AND";
        }

        if(isset($afterDatetime)){
            $sql .= ' ' . $where . ' user.created_at > "' . $afterDatetime . '"';
        }

        $result = $this->createQuery($sql);
        return $result->fetch(\PDO::FETCH_NUM)[0];
    }

    public function getUsers(array $parameters = []) : array
    {
        $where = "WHERE";

        extract($parameters);

        $sql = 'SELECT user.id, user.created_at, user.firstname, user.lastname, user.pseudo, user.mail, user.phone, user.score, user.status_id, user.role_id,
                user_status.name as status_name,
                user_role.name as role_name
                FROM user
                INNER JOIN user_role ON user.role_id = user_role.id
                INNER JOIN user_status ON user.status_id = user_status.id';

        if(isset($q)){
            $sql .= ' ' . $where . ' user.pseudo LIKE "%' . $q . '%"';
            $where = "AND";
        }

        if(isset($scoreHigherThan)){
            $sql .= ' ' . $where . ' user.score > "' . $scoreHigherThan . '"' ;
            $where = "AND";
        }

        if(isset($scoreLowerThan)){
            $sql .= ' ' . $where . ' user.score < "' . $scoreLowerThan . '"';
        }

        if(!isset($allUserStatus)){
            if(isset($online) || isset($offline) || isset($banned)){
                $or = "";
                $sql .= ' ' . $where . ' (' ;
                if(isset($online)){
                    $sql .= ' user_status.name = "online"';
                    $or = " OR ";
                }
                if(isset($offline)){
                    $sql .= $or . ' user_status.name = "offline"';
                    $or = " OR ";
                }
                if(isset($banned)){
                    $sql .= $or . ' user_status.name = "banned"';
                }
                $sql .= ')';
                $where = "AND";
            }
        }

        if(!isset($allUserRoles)){
            if(isset($admin) || isset($moderator) || isset($editor) || isset($user)){
                $or = "";
                $sql .= ' ' . $where . ' (' ;
                if(isset($admin)){
                    $sql .= ' user_role.name = "admin"';
                    $or = " OR ";
                }                
                if(isset($moderator)){
                    $sql .= $or . ' user_role.name = "moderator"';
                    $or = " OR ";
                }
                if(isset($editor)){
                    $sql .= $or . ' user_role.name = "editor"';
                    $or = " OR ";
                }
                if(isset($user)){
                    $sql .= $or . ' user_role.name = "user"';
                }
                $sql .= ')';
                $where = "AND";
            }
        }
        
        if(isset($beforeDatetime)){
            $sql .= ' ' . $where . ' user.created_at < "' . $beforeDatetime . '"' ;
            $where = "AND";
        }

        if(isset($afterDatetime)){
            $sql .= ' ' . $where . ' user.created_at > "' . $afterDatetime . '"';
        }
        
        if(isset($orderby)){
            $sql .= ' ORDER BY created_at ' . $orderby;
        } else {
            $sql .= ' ORDER BY created_at DESC';
        }

        if(isset($limit)){
            $sql .= " LIMIT $limit";
            if(isset($offset)){
                $sql .= " OFFSET $offset";
            }
        }

        $result = $this->createQuery($sql);
        $users = [];
        foreach ($result as $row){
            $userId = $row['id'];
            $users[$userId] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $users;
    }
}