<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\src\model\Category;

class CategoryDAO extends DAO
{
    private function buildObject($row)
    {
        $category = new Category();
        $category->setId($row['id']);
        $category->setName($row['name']);
        $category->setParentId($row['parent_id']);
        // $category->setParentName($row['parent_name']);
        return $category;
    }

    public function getCategories(array $parameters = []) : array
    {
        $where = "WHERE";

        extract($parameters);

        $sql = 'SELECT category.id, category.name, category.parent_id FROM category';

        $result = $this->createQuery($sql);
        $categories = [];
        foreach ($result as $row){
            $category = $row['id'];
            $categories[$category] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $categories;
    }
}