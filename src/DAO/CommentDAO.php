<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\src\model\Comment;

class CommentDAO extends DAO
{
    private function buildObject($row)
    {
        $comment = new Comment();
        $comment->setId($row['id']);
        $comment->setCreatedAt($row['created_at']);
        $comment->setLastModified($row['last_modified']);
        $comment->setUserId($row['user_id']);
        $comment->setUserPseudo($row['user_pseudo']);
        $comment->setArticleId($row['article_id']);
        $comment->setArticleTitle($row['article_title']);
        $comment->setContent($row['content']);
        $comment->setValidated($row['validated']);
        $comment->setAnswerTo($row['answer_to']);  
        return $comment;
    }
}