<?php

namespace App\Src\DAO;

use App\Config\Parameter;
use App\Src\Model\Comment;

class CommentDAO extends DAO
{
    private function buildObject($row) : Comment
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
        $this->query = $this->selectComments()->where('c.id = :commentId');

        $result = $this->createQuery((string)$this->query,['commentId' => $commentId]);

        if ($comment = $result->fetch()) {
            $comment = $this->buildObject($comment);
        }

        $result->closeCursor();

        return $comment;
    }

    public function addComment (array $parameters) : void
    {
        $this->query = (new QueryBuilder()) 
            ->statement('insert')
            ->table('comment')
            ->insertValues([
                'user_id' => ':userId',
                'article_id' => ':articleId',
                'created_at' => ':createdAt',
                'last_modified' => ':lastModified',
                'content' => ':content',
                'validated' => ':validated',
                'answer_to' => ':answerTo'
            ]);

        $pdo = $this->prepareQuery((string)$this->query);

        $pdo->bindValue(':userId', $parameters['userId']);
        $pdo->bindValue(':articleId', $parameters['articleId']);
        $pdo->bindValue(':createdAt', $parameters['createdAt']);
        $pdo->bindValue(':lastModified', null);
        $pdo->bindValue(':content', $parameters['content']);    
        $pdo->bindValue(':validated', $parameters['validated']);
        $pdo->bindValue(':answerTo', $parameters['answerTo']);

        $pdo->execute();  
                                   
    }

    public function editComment (string $content, int $commentId, string $lastModified,int $validated = 0) : void
    {
        $this->query = (new QueryBuilder())
            ->statement('update')
            ->table('comment')
            ->set('content = :content, last_modified = :lastModified, validated = :validated')
            ->where('id = :commentId');

        $this->createQuery((string)$this->query,[
            'content' => $content,
            'validated' => $validated,
            'lastModified' => $lastModified,
            'commentId' => $commentId
        ]);
    }

    public function deleteComment(int $commentId) : void
    {
        $this->query = (new QueryBuilder()) 
            ->statement('delete')
            ->table('comment')
            ->where('answer_to = :commentId');

        $this->createQuery((string)$this->query, ['commentId' => $commentId]);

        $this->query = (new QueryBuilder()) 
            ->statement('delete')
            ->table('comment')
            ->where('id = :commentId');

        $this->createQuery((string)$this->query, ['commentId' => $commentId]);
    }

    public function updateCommentValidation(int $commentId, int $validated) : void
    {   
        $this->query = (new QueryBuilder()) 
            ->statement('update')
            ->table('comment')
            ->set('validated = :validated')
            ->where('id = :commentId');

        $this->createQuery((string)$this->query,[
            'validated' => $validated,
            'commentId' => $commentId
        ]);
    }

    public function countComments(array $parameters = []) : int
    {
        $this->query = (new QueryBuilder()) 
            ->statement('select')
            ->count(1)
            ->table('comment','c')
            ->innerJoin(['u'=>'user'], 'c.user_id = u.id');

        $parameters = $this->addParameters($parameters);

        $result = $this->createQuery((string)$this->query, $parameters);

        return $result->fetch(\PDO::FETCH_NUM)[0];
    }

    public function getComments(array $parameters = []) : array
    {
        $this->query = $this->selectComments();

        $parameters = $this->addParameters($parameters);

        $result = $this->createQuery((string)$this->query, $parameters);

        $comments = [];

        foreach ($result as $row) {
            $commentId = $row['id'];
            $comments[$commentId] = $this->buildObject($row);
        }

        $result->closeCursor();
        
        return $comments;
    }

    private function addParameters(array $parameters = []) : array
    {
        if (isset($parameters['q'])) {
            $this->query->where('c.content LIKE "%' . htmlentities($parameters['q']) . '%"');
            unset($parameters['q']);
        }

        if (isset($parameters['userId'])) {
            $this->query->where('u.id = :userId');
        }

        if (isset($parameters['articleId'])) {
            $this->query->where('c.article_id = :articleId');
        }

        if (isset($parameters['validated'])) {
            if ($parameters['validated'] === "validated") {
                $parameters['validated'] = 1;
            } elseif ($parameters['validated'] === "pending") {
                $parameters['validated'] = 0;
            }
            $this->query->where('c.validated = :validated');
        }

        if (isset($parameters['beforeDatetime'])) {
            $this->query->where('c.created_at < :beforeDatetime');
        }
        
        if (isset($parameters['afterDatetime'])) {
            $this->query->where('c.created_at > :afterDatetime');
        }

        if (isset($parameters['orderBy'])) {
            $this->query->orderBy($parameters['orderBy']);
            unset($parameters['orderBy']);
        } else {
            $this->query->orderBy(['column' => 'c.created_at', 'order' => 'DESC']);
            unset($parameters['orderBy']);
        }

        if (isset($parameters['limit'])) {
            $this->query->limit($parameters['limit']);
            unset($parameters['limit']);
            if (isset($parameters['offset'])) {
                $this->query->offset($parameters['offset']);
                unset($parameters['offset']);
            }
        }

        return $parameters;
    }

    private function selectComments() : QueryBuilder
    {
        return (new QueryBuilder()) 
            ->statement('select')
            ->select(
                'c.id',
                'c.user_id',
                'c.article_id',
                'c.created_at',
                'c.last_modified', 'c.content',
                'c.validated',
                'c.answer_to',
                'u.pseudo as user_pseudo',
                'a.title as article_title'
            )
            ->table('comment','c')
            ->innerJoin(['a'=>'article'], 'c.article_id = a.id')
            ->leftJoin(['u'=>'user'], 'c.user_id = u.id');
    }
}