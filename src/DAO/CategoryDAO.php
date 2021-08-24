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
        $category->setParentName($row['parent_name']);
        return $category;
    }
}