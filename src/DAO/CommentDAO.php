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

    public function getComment(int $commentId) : mixed
    {
        $sql = 'SELECT comment.id, comment.user_id, comment.article_id, comment.created_at, comment.last_modified, comment.content, comment.validated, comment.answer_to,
                       user.pseudo as user_pseudo,
                       article.title as article_title
                FROM comment
                INNER JOIN article ON comment.article_id = article.id
                INNER JOIN user ON comment.user_id = user.id
                WHERE comment.id = :comment_id';
        $result = $this->createQuery($sql,[
            'comment_id' => $commentId
        ]);
        $comment = $result->fetch();
        if($comment){
            $comment = $this->buildObject($comment);
        }
        $result->closeCursor();
        return $comment;
    }

    // ok
    public function addComment (array $parameters) : void
    {
        extract($parameters);

        if(!isset($answerTo)){
            $answerTo = null;
        }
        
        $sql ='INSERT INTO comment (user_id, article_id, created_at, last_modified, content, validated, answer_to)
               VALUES (:user_id, :article_id, NOW(), null, :content, :validated, :answer_to)';
        $this->createQuery($sql, [ 'user_id' => $userId,
                                   'article_id' => $articleId,
                                   'content' => $content,
                                   'validated' => $validated,
                                   'answer_to' => $answerTo]);
                                   
    }

    // ok
    public function editComment (string $content, int $commentId,int $validated = 0) : void
    {
        $sql = 'UPDATE comment SET content = :content, last_modified = NOW(), validated = :validated WHERE id = :comment_id';
        $this->createQuery($sql,['content' => $content,
                                 'comment_id' => $commentId,
                                 'validated' => $validated
                                ]);
    }

    // ok
    public function deleteComment(int $commentId)
    {
        $sql = 'DELETE FROM comment WHERE answer_to = :comment_id;
                DELETE FROM comment WHERE id = :comment_id';
        $this->createQuery($sql, ['comment_id' => $commentId]);
    }

    // ok
    public function updateCommentValidation(int $commentId, int $validated) : void
    {       
        $sql = 'UPDATE comment SET validated = :validated WHERE id = :comment_id';
        $this->createQuery($sql,[
            'validated' => $validated,
            'comment_id' => $commentId
        ]);
    }

    public function countComments(array $parameters = []) : int
    {
        $where = "WHERE";

        extract($parameters);

        $sql = 'SELECT COUNT(comment.id) FROM comment 
                INNER JOIN user ON comment.user_id = user.id';
        
        if(isset($q)){
            $sql .= ' ' . $where . ' comment.content LIKE "%' . $q . '%"';
            $where = "AND";
        }

        if(isset($userId)){
            $sql .= ' ' . $where . ' user.id =' . $userId ;
            $where = "AND";
        }

        if(isset($articleId)){
            $sql .= ' ' . $where . ' comment.article_id = ' . $articleId;
            $where = "AND";
        }

        if(isset($validated)){
            if($validated === "validated"){
                $validated = 1;
            } elseif($validated === "pending"){
                $validated = 0;
            }
            $sql .= ' ' . $where . ' comment.validated = ' . $validated ;
            $where = "AND";
        }

        if(isset($beforeDatetime)){
            $sql .= ' ' . $where . ' comment.created_at < "' . $beforeDatetime . '"' ;
            $where = "AND";
        }

        if(isset($afterDatetime)){
            $sql .= ' ' . $where . ' comment.created_at > "' . $afterDatetime . '"';
            $where = "AND";
        }

        $result = $this->createQuery($sql);
        return $result->fetch(\PDO::FETCH_NUM)[0];
    }

    public function getComments(array $parameters = []) : array
    {
        $where = "WHERE";

        extract($parameters);

        $sql = 'SELECT comment.id, comment.user_id, comment.article_id, comment.created_at, comment.last_modified, comment.content, comment.validated, comment.answer_to,
        user.pseudo as user_pseudo,
        article.title as article_title
        FROM comment
        INNER JOIN article ON comment.article_id = article.id
        INNER JOIN user ON comment.user_id = user.id';
        
        if(isset($q)){
            $sql .= ' ' . $where . ' comment.content LIKE "%' . $q . '%"';
            $where = "AND";
        }

        if(isset($userId)){
            $sql .= ' ' . $where . ' user.id =' . $userId ;
            $where = "AND";
        }

        if(isset($articleId)){
            $sql .= ' ' . $where . ' comment.article_id = ' . $articleId;
            $where = "AND";
        }

        if(isset($validated)){
            if($validated === "validated"){
                $validated = 1;
            } elseif($validated === "pending"){
                $validated = 0;
            }
            $sql .= ' ' . $where . ' comment.validated = ' . $validated ;
            $where = "AND";
        }

        if(isset($beforeDatetime)){
            $sql .= ' ' . $where . ' comment.created_at < "' . $beforeDatetime . '"' ;
            $where = "AND";
        }

        if(isset($afterDatetime)){
            $sql .= ' ' . $where . ' comment.created_at > "' . $afterDatetime . '"';
            $where = "AND";
        }

        if(isset($orderby)){
            $sql .= ' ORDER BY created_at ' . $orderby;
        } else {
            $sql .= ' ORDER BY created_at ASC';
        }
        
        if(isset($limit)){
            $sql .= ' LIMIT ' . $limit;
            if(isset($offset)){
                $sql .= " OFFSET $offset";
            }
        }


        
        $result = $this->createQuery($sql);
        $comments = [];
        foreach ($result as $row){
            $commentId = $row['id'];
            $comments[$commentId] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $comments;
    }
}