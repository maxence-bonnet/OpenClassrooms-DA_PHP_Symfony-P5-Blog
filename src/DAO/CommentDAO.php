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

    // ok
    public function getCommentsFromArticle(int $articleId) : array
    {
        $sql = 'SELECT comment.id, comment.user_id, comment.article_id, comment.created_at, comment.last_modified, comment.content, comment.validated, comment.answer_to,
                       user.pseudo as user_pseudo,
                       article.title as article_title
                FROM comment
                INNER JOIN article ON comment.article_id = article.id
                INNER JOIN user ON comment.user_id = user.id
                WHERE article_id = :article_id
                AND comment.validated = :validated';

        $result = $this->createQuery($sql,[
            'article_id' => $articleId,
            'validated' => 1
        ]);
        $comments = [];
        foreach ($result as $row){
            $commentId = $row['id'];
            $comments[$commentId] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $comments;
    }

    // ok
    public function getComment(int $commentId)
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

    public function getPendingComments(array $parameters = []) 
    {
        $sql = 'SELECT comment.id, comment.user_id, comment.article_id, comment.created_at, comment.last_modified, comment.content, comment.validated, comment.answer_to,
        user.pseudo as user_pseudo,
        article.title as article_title
        FROM comment
        INNER JOIN article ON comment.article_id = article.id
        INNER JOIN user ON comment.user_id = user.id
        AND comment.validated = :validated';
        if(isset($parameters['limit'])){
            $sql .= ' LIMIT '. (int) $parameters['limit'];
        }
        
        $result = $this->createQuery($sql,[
            'validated' => 0
        ]);
        $comments = [];
        foreach ($result as $row){
            $commentId = $row['id'];
            $comments[$commentId] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $comments;
    }

    // ok
    public function addComment (Parameter $post, int $articledId, int $userId)
    {
        $answerTo = null;
        if($post->get('answer_to')){
            $answerTo = $post->get('answer_to');
        }
        $sql ='INSERT INTO comment (user_id, article_id, created_at, last_modified, content, validated, answer_to)
               VALUES (:user_id, :article_id, NOW(), null, :content, :validated, :answer_to)';
        $this->createQuery($sql, [ 'user_id' => $userId,
                                   'article_id' => $articledId,
                                   'content' => $post->get('content'),
                                   'validated' => "0",
                                   'answer_to' => $answerTo]);
    }

    // ok
    public function editComment (Parameter $post, int $commentId)
    {
        $sql = 'UPDATE comment SET content = :content, last_modified = NOW(), validated = :validated WHERE id = :comment_id';
        $this->createQuery($sql,['content' => $post->get('content'),
                                 'comment_id' => $commentId,
                                 'validated' => 0
                                ]);
    }


    // ok
    public function deleteComment(int $commentId)
    {
        $sql = 'DELETE FROM comment WHERE id = :comment_id';
        $this->createQuery($sql, ['comment_id' => $commentId]);
    }

    public function validateComment(int $commentId)
    {
        $sql = 'UPDATE comment SET validated = :validated WHERE id = :comment_id';
        $this->createQuery($sql,[
            'validated' => 1,
            'comment_id' => $commentId
        ]);
    }
}