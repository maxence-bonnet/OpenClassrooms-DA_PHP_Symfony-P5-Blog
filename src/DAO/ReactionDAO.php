<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\src\model\Reaction;

class ReactionDAO extends DAO
{
    private function buildObject($row)
    {
        $reaction = new Reaction();
        $reaction->setArticleId($row['article_id']);  
        $reaction->setUserId($row['user_id']);        
        $reaction->setUserPseudo($row['user_pseudo']);
        $reaction->setName($row['name']);
        $reaction->setCommentId($row['comment_id']);
        return $reaction;
    }
}