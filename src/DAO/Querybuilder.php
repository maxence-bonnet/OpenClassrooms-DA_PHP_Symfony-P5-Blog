<?php

namespace App\src\DAO;
use Exception;

class QueryBuilder
{
    private $statement;
    private $select;
    private $set;
    private $insert;
    private $count;
    private $table;
    private $join;
    private $where;
    private $order;
    private $limit;
    private $offset;

    public function statement(string $statement) : self
    {
        switch ($statement) {
            case 'select' :
                $this->statement = 'SELECT';
                break;
            case 'insert' :
                $this->statement = 'INSERT INTO';
                break;
            case 'update' :
                $this->statement = 'UPDATE';
                break;
            case 'delete' :
                $this->statement = 'DELETE';    
                break;
            default :
                throw new Exception ('Syntax error in ->statement : ' . $connectionError->getMessage());
        }
        return $this;
    }

    public function table(string $table, ?string $alias = null) : self
    {
        if($alias){
            $this->table[$alias] = $table;
        } else {
            $this->table[] = $table;
        }
        return $this;
    }

    private function eachTable() : string
    {
        $tables = [];
        foreach($this->table as $key => $value){
            if(is_string($key)){
                $tables[] = "$value as $key";
            } else {
                $tables[] = $value;
            }
        }
        return join(', ',$tables);
    }

    public function insertValues(array $insertValues) : self
    {
        foreach($insertValues as $key => $value){
            $inColumns[] = $key;
            $values[] = $value;
        }
        $this->insert = "(" . join(', ', $inColumns) . ") VALUES(" . join(', ', $values) . ")";
        return $this;
    }

    public function set(string ...$setValues) : self
    {
        $this->set = $setValues;
        return $this;
    }

    public function select(string ...$fields) : self
    {
        $this->select = $fields;
        return $this;
    }

    public function innerJoin(array $table, string $condition) : self
    {
        foreach($table as $key => $value){
            if(is_string($key)){
                $join = "$value as $key";
            } else {
                $join = "$value";
            }
        }
        $this->join[] = "INNER JOIN $join ON $condition";
        return $this;
    }

    public function count(string $field) : self
    {
        $this->select = ["COUNT($field)"];
        return $this;
    }

    public function where(string $condition) : self
    {
        $this->where[] = $condition;
        return $this;
    }

    public function subWhere(string $condition) : self
    {
        $this->where[] = "(" . $condition . ")";
        return $this;
    }

    public function limit(int $limit) : self
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function offset(int $offset) : self
    {
        $this->offset = "OFFSET $offset";
        return $this;
    }

    public function order(string $column, string $order = 'ASC') : self
    {
        if($order === 'ASC' || $order === 'DESC'){
            $this->order = "ORDER BY $column $order";
        }    
        return $this;
    }
    public function __toString() : string
    {
        $parts[] = $this->statement;
        if($this->select){
            $parts[] = join(', ',$this->select);
        }
        // FROM
        if($this->statement === 'SELECT' || $this->statement === 'DELETE'){
            $parts[] = 'FROM';
        }
        $parts[] = $this->eachTable();
        if($this->statement === 'INSERT INTO'){
            $parts[] = $this->insert;
        }
        // (IF UPDATE => SET)
        if($this->statement === 'UPDATE' && $this->set){
            $parts[] = 'SET';
            $parts[] = join(', ', $this->set);
        }
        // JOIN
        if($this->join){
            foreach($this->join as $join){
                $parts[] = $join;
            }
        }
        // WHERE
        if($this->where){
            $parts[] = "WHERE";
            foreach($this->where as $key => $condition){
                $parts[] = $condition;
                if($key !== array_key_last($this->where)){
                    $parts[] = "AND";
                }
            }          
        }
        // ORDER BY
        if($this->order){
            $parts[] = $this->order;
        }
        // LIMIT & OFFSET
        if($this->limit){
            $parts[] = $this->limit;
            if($this->offset){
                $parts[] = $this->offset;
            }
        }
        return join(' ', $parts);
    }
}