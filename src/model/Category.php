<?php

namespace App\Src\Model;

class Category
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;    

    /**
     * @var int/null
     */
    private $parentId;

    /**
     * @var string/null
     */
    // private $parentName;


    /**
     * @return int
     */
    public function getId(){return $this->id;}

    /**
     * @return string
     */
    public function getName(){return $this->name;}

    /**
     * @return int/null
     */
    public function getParentId(){return $this->parentId;}

    
    /**
     * @param int $id
     */
    public function setId($id){$this->id = $id;}

    /**
     * @param string $name
     */
    public function setName($name){$this->name = $name;}

    /**
     * @param string $parentId
     */
    public function setParentId($parentId){$this->parentId = $parentId;}
}