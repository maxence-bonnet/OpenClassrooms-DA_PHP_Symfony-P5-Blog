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
                
        $parameters['article_id'] = $articleId;
        $parameters['validated'] = "1"; 
        $result = $this->createQuery($sql,$parameters);
        $comments = [];
        foreach ($result as $row){
            $commentId = $row['id'];
            $comments[$commentId] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $comments;
    }

    public function getPendingComments() 
    {
        // For administration stuff
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
                                   'validated' => "1",
                                   'answer_to' => $answerTo]);
    }

    public function edditComment (Parameter $post, int $commentId)
    {
        // User will be able to update his comment (new validation required)
    }


    // ok
    public function deleteComment(int $commentId)
    {
        $sql = 'DELETE FROM comment WHERE id = :comment_id';
        $this->createQuery($sql, ['comment_id' => $commentId]);
    }
}