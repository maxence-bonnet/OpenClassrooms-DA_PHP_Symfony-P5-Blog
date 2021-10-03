<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\src\model\Category;

class CategoryDAO extends DAO
{
    private function buildObject(array $row) : Category
    {
        $category = new Category();
        $category->setId($row['id']);
        $category->setName($row['name']);
        $category->setParentId($row['parent_id']);
        // $category->setParentName($row['parent_name']);
        return $category;
    }

    public function getCategory(int $categoryId) : mixed
    {
        $this->query = (new QueryBuilder()) ->statement('select')
                                            ->select('c.id', 'c.name', 'c.parent_id')
                                            ->table('category','c')
                                            ->where('id = :categoryId');
        $result = $this->createQuery((string)$this->query, ['categoryId' => $categoryId]);
        if($category = $result->fetch()){
            $category = $this->buildObject($category);
        }
        $result->closeCursor();
        return $category;                           
    }

    public function getCategories(array $parameters = []) : array
    {
        $this->query = (new QueryBuilder()) ->statement('select')
                                            ->select('c.id', 'c.name', 'c.parent_id')
                                            ->table('category','c');

        $result = $this->createQuery((string)$this->query);
        
        $categories = [];
        foreach ($result as $row){
            $category = $row['id'];
            $categories[$category] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $categories;
    }
}