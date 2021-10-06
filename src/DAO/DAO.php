<?php

namespace App\Src\DAO;

use PDO;
use PDOStatement;
use Exception;

abstract class DAO
{
    private $connection;
    
    protected $query;
    
    private function checkConnection()
    {
        if ($this->connection === null) {
            return $this->getConnection();
        }

        return $this->connection;
    }

    private function getConnection()
    {
        try {
            $this->connection = new PDO(DB_HOST,DB_USER,DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        } catch(Exception $connectionError) {
            throw new Exception('Erreur lors de la connexion à la base de données : ' . $connectionError->getMessage());
        }
    }
    
    protected function createQuery($sql, $parameters = null)
    {
        if ($parameters) {
            $result = $this->checkConnection()->prepare($sql);
            $result->execute($parameters);
            return $result;
        }

        $result = $this->checkConnection()->query($sql);
        
        return $result;
    }

    protected function prepareQuery(string $sql) : PDOStatement
    {
        return $this->checkConnection()->prepare($sql);
    }
}