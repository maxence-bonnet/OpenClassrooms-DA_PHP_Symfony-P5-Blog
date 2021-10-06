<?php

namespace App\Src\DAO;

use App\Config\Parameter;
use App\Src\Model\User;
use PDO;


class UserDAO extends DAO
{
    private function buildObject(array $row) : User
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
  
    public function getUser(int $userId) : mixed
    {
        $this->query = $this->selectUsers()->where('u.id = :userId');

        $result = $this->createQuery((string)$this->query, ['userId' => $userId]);

        if ($user = $result->fetch()) {
            $user = $this->buildObject($user);
        }

        $result->closeCursor();
        return $user;
    }
   
    public function updateUserRole(int $userId, int $roleId) : void
    {
        $this->query = (new QueryBuilder()) 
            ->statement('update')
            ->table('user')
            ->set('role_id = :roleId')
            ->where('id = :userId');

        $this->createQuery((string)$this->query,[
            'userId' => $userId,
            'roleId' => $roleId
        ]);
    }

    public function updateUserStatus(int $userId, int $statusId) : void
    {
        $this->query = (new QueryBuilder()) 
            ->statement('update')
            ->table('user')
            ->set('status_id = :statusId')
            ->where('id = :userId');

        $this->createQuery((string)$this->query,[
            'userId' => $userId,
            'statusId' => $statusId
        ]);
    }

    public function pseudoExists(string $pseudo) : ?int
    {
        $this->query = (new QueryBuilder()) 
            ->statement('select')
            ->table('user')
            ->count(1)
            ->where('pseudo = :pseudo');

        $result = $this->createQuery((string)$this->query, [':pseudo' => $pseudo]);

        return $result->fetchColumn();
    }

    public function register(Parameter $post) : void
    {
        $this->query = (new QueryBuilder()) 
            ->statement('insert') 
            ->table('user')
            ->insertValues([
                'created_at' => ':createdAt',
                'firstname' => ':firstname',
                'lastname' => ':lastname',
                'pseudo' => ':pseudo',
                'password' => ':password',
                'email' => ':email',
                'phone' => ':phone',
                'role_id' => 2,
                'status_id' => 1,
                'score' => 0
            ]);

        $pdo = $this->prepareQuery((string)$this->query);

        $pdo->bindValue(':createdAt', $post->get('createdAt'));
        $pdo->bindValue(':firstname', $post->get('firstname'));
        $pdo->bindValue(':lastname', $post->get('lastname'));
        $pdo->bindValue(':pseudo', $post->get('pseudo'));
        $pdo->bindValue(':password', $post->get('password'));
        $pdo->bindValue(':email', $post->get('email'));
        $pdo->bindValue(':phone', $post->get('phone'));

        $pdo->execute();
    }
 
    public function login(Parameter $post) : array
    {
        $this->query = (new QueryBuilder()) 
            ->statement('select')
            ->select(
                'u.id',
                'pseudo',
                'password',
                's.name as status_name',
                'r.name as role_name'
            )
            ->table('user', 'u')
            ->innerJoin(['r' => 'user_role'],'u.role_id = r.id')
            ->innerJoin(['s' => 'user_status'],'u.status_id = s.id')
            ->where('u.pseudo = :pseudo');

        return $this->createQuery($this->query, ['pseudo' => $post->get('pseudo')])->fetch(PDO::FETCH_ASSOC);
    }

    public function logout(int $userId) : void
    {
        $this->query = (new QueryBuilder()) 
            ->statement('update')
            ->table('user')
            ->set('status_id = 1')
            ->where('id = :id')
            ->where('status_id != 3');

        $this->createQuery((string)$this->query, ['id' => $userId]);
    }
  
    public function countUsers(array $parameters = []) : int
    {
        $this->query = (new QueryBuilder()) 
            ->statement('select')
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
        $this->query = $this->selectUsers();

        $parameters = $this->addParameters($parameters);

        $result = $this->createQuery((string)$this->query,$parameters);

        $users = [];

        foreach ($result as $row) {
            $userId = $row['id'];
            $users[$userId] = $this->buildObject($row);
        }

        $result->closeCursor();

        return $users;
    }
 
    private function addParameters(array $parameters = []) : array
    {
        if (isset($parameters['q'])) {
            $this->query->where('u.pseudo LIKE "%' . htmlentities($parameters['q']) . '%"');
            unset($parameters['q']);
        }
        
        if (isset($parameters['scoreHigherThan'])) {
            $this->query->where('u.score > :scoreHigherThan');
        }
        
        if (isset($parameters['scoreLowerThan'])) {
            $this->query->where('u.score < :scoreLowerThan');
        }
        
        if (isset($parameters['online']) || isset($parameters['offline']) || isset($parameters['banned'])) {
            $conditions = [];
            if (isset($parameters['online'])) {
                $conditions[] = 's.name = :online';
                $parameters['online'] = 'online';
            }
            if (isset($parameters['offline'])) {
                $conditions[] = 's.name = :offline';
                $parameters['offline'] = 'offline';
            }
            if (isset($parameters['banned'])) {
                $conditions[] = 's.name = :banned';
                $parameters['banned'] = 'banned';
            }
            $this->query->subWhere(join(' OR ', $conditions));
        }
        
        if (isset($parameters['admin']) || isset($parameters['moderator']) || isset($parameters['editor']) || isset($parameters['user'])) {
            $conditions = [];
            if (isset($parameters['admin'])) {
                $conditions[] = 'r.name = :admin';
                $parameters['admin'] = 'admin';
            }
            if (isset($parameters['moderator'])) {
                $conditions[] = 'r.name = :moderator';
                $parameters['moderator'] = 'moderator';
            }
            if (isset($parameters['editor'])) {
                $conditions[] = 'r.name = :editor';
                $parameters['editor'] = 'editor';
            }
            if (isset($parameters['user'])) {
                $conditions[] = 'r.name = :user';
                $parameters['user'] = 'user';
            }
            $this->query->subWhere(join(' OR ', $conditions));
        }

        if (isset($parameters['beforeDatetime'])) {
           $this->query->where('u.created_at < :beforeDatetime');
        }
        
        if (isset($parameters['afterDatetime'])) {
            $this->query->where('u.created_at > :afterDatetime');
        }

        if (isset($parameters['orderBy'])) {
            $this->query->orderBy($parameters['orderBy']);
            unset($parameters['orderBy']);
        } else {
            $this->query->orderBy(['column'=>'u.created_at','order'=>'DESC']);
            unset($parameters['orderBy']);
        }

        if (isset($parameters['limit'])) {
            $this->query->limit($parameters['limit']);
            unset($parameters['limit']);
            if (isset($parameters['offset'])) {
                $this->query->offset($parameters['offset']);
                unset($parameters['offset']);
            }
        }

        return $parameters;
    }

    private function selectUsers() : QueryBuilder
    {
        return (new QueryBuilder()) 
            ->statement('select')
            ->select(
                'u.id','u.created_at',
                'u.firstname',
                'u.lastname',
                'u.pseudo', 'u.email',
                'u.phone', 'u.score',
                'u.status_id',
                'u.role_id',
                's.name as status_name',
                'r.name as role_name'
            )
            ->table('user', 'u')
            ->innerJoin(['r' => 'user_role'],'u.role_id = r.id')
            ->innerJoin(['s' => 'user_status'],'u.status_id = s.id');
    }
}