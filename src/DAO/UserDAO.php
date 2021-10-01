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
        $user->setEmail($row['email']);
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
        $sql = 'SELECT user.id, user.created_at, user.firstname, user.lastname, user.pseudo, user.email, user.phone, user.score, user.status_id, user.role_id,
        user_status.name as status_name,
        user_role.name as role_name
        FROM user
        INNER JOIN user_role ON user.role_id = user_role.id
        INNER JOIN user_status ON user.status_id = user_status.id
        WHERE user.id = :user_id';

        $result = $this->createQuery($sql, ['user_id' => $userId]);
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
        $result = $this->createQuery($sql, [':pseudo' => htmlspecialchars($pseudo)]);
        return $result->fetchColumn();;
    }

    public function register(Parameter $post) : void
    {
        $sql = 'INSERT INTO user (created_at, firstname, lastname, pseudo, password, email, phone, role_id, status_id, score)
                VALUES(NOW(), :firstname, :lastname, :pseudo, :password, :email, :phone, :role_id, :status_id, :score)';
        $this->createQuery($sql, [
            'firstname' => $post->get('firstname'),
            'lastname' => $post->get('lastname'),
            'pseudo' => $post->get('pseudo'),
            'password' => password_hash($post->get('password'),PASSWORD_BCRYPT),
            'email' => $post->get('email'),
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
        $this->query = (new QueryBuilder()) ->statement('update')
                                            ->table('user')
                                            ->set('status_id = 1')
                                            ->where('id = :id')
                                            ->where('status_id != 3');
        $this->createQuery((string)$this->query, ['id' => $userId]);
    }

    public function countUsers(array $parameters = []) : int
    {
        $this->query = (new QueryBuilder()) ->statement('select')
                                            ->count(1)
                                            ->table('user', 'u')
                                            ->innerJoin(['r' => 'user_role'],'u.role_id = r.id')
                                            ->innerJoin(['s' => 'user_status'],'u.status_id = s.id');
        
        $parameters = $this->addParameters($parameters);

        $result = $this->createQuery((string)$this->query,$parameters);
        return $result->fetch(\PDO::FETCH_NUM)[0];
    }

    public function getUsers(array $parameters = []) : array
    {
        $this->query = (new QueryBuilder()) ->statement('select')
                                            ->select('u.id','u.created_at', 'u.firstname', 'u.lastname', 'u.pseudo', 'u.email', 'u.phone', 'u.score', 'u.status_id', 'u.role_id',
                                            's.name as status_name',
                                            'r.name as role_name')
                                            ->table('user', 'u')
                                            ->innerJoin(['r' => 'user_role'],'u.role_id = r.id')
                                            ->innerJoin(['s' => 'user_status'],'u.status_id = s.id');

        $parameters = $this->addParameters($parameters);

        $result = $this->createQuery((string)$this->query,$parameters);
        $users = [];
        foreach ($result as $row){
            $userId = $row['id'];
            $users[$userId] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $users;
    }

    private function addParameters(array $parameters = []) : array
    {
        if(isset($parameters['q'])){
            $this->query->where('u.pseudo LIKE "%' . htmlentities($parameters['q']) . '%"');
            unset($parameters['q']);
        }
        
        if(isset($parameters['scoreHigherThan'])){
            $this->query->where('u.score > :scoreHigherThan');
        }
        
        if(isset($parameters['scoreLowerThan'])){
            $this->query->where('u.score < :scoreLowerThan');
        }
        
        if(isset($parameters['online']) || isset($parameters['offline']) || isset($parameters['banned'])){
            $conditions = [];
            if(isset($parameters['online'])){
                $conditions[] = 's.name = :online';
                $parameters['online'] = 'online';
            }
            if(isset($parameters['offline'])){
                $conditions[] = 's.name = :offline';
                $parameters['offline'] = 'offline';
            }
            if(isset($parameters['banned'])){
                $conditions[] = 's.name = :banned';
            }
            $this->query->subWhere(join(' OR ', $conditions));
        }
        
        if(isset($parameters['admin']) || isset($parameters['moderator']) || isset($parameters['editor']) || isset($parameters['user'])){
            $conditions = [];
            if(isset($parameters['admin'])){
                $conditions[] = 'r.name = :admin';
                $parameters['admin'] = 'admin';
            }
            if(isset($parameters['moderator'])){
                $conditions[] = 'r.name = :moderator';
                $parameters['moderator'] = 'moderator';
            }
            if(isset($parameters['editor'])){
                $conditions[] = 'r.name = :editor';
                $parameters['editor'] = 'editor';
            }
            if(isset($parameters['user'])){
                $conditions[] = 'r.name = :user';
                $parameters['user'] = 'user';
            }
            $this->query->subWhere(join(' OR ', $conditions));
        }
        
        
        if(isset($parameters['beforeDatetime'])){
           $this->query->where('u.created_at < :beforeDatetime');
        }
        
        if(isset($parameters['afterDatetime'])){
            $this->query->where('u.created_at > :afterDatetime');
        }

        if(isset($parameters['orderBy'])){
            $this->query->order($parameters['orderBy']);
            unset($parameters['orderBy']);
        } else {
            $this->query->order('created_at','DESC');
        }

        if(isset($parameters['limit'])){
            $this->query->limit($parameters['limit']);
            unset($parameters['limit']);
            if(isset($parameters['offset'])){
                $this->query->offset($parameters['offset']);
                unset($parameters['offset']);
            }
        }

        return $parameters;
    }
}