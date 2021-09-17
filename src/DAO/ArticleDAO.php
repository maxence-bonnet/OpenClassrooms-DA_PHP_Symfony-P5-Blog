<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\src\model\Article;

class ArticleDAO extends DAO
{

    private function buildObject($row)
    {
        $article = new Article();
        $article->setId($row['id']);
        $article->setCreatedAt($row['created_at']);
        $article->setLastModified($row['last_modified']);
        $article->setTitle($row['title']);
        $article->setLede($row['lede']);
        $article->setContent($row['content']);
        $article->setAuthorId($row['author_id']);
        $article->setAuthorPseudo($row['author_pseudo']);
        $article->setCategoryId($row['category_id']);
        $article->setCategoryName($row['category_name']);
        $article->setStatusId($row['status_id']);
        $article->setStatusName($row['status_name']);
        $article->setAllowComment($row['allow_comment']);
        return $article;
    }

    /**
     * Returns one article from its id
     */
    public function getArticle(int $articleId)
    {
        $sql = 'SELECT article.id, article.created_at, article.last_modified, article.title, article.lede, article.content, article.author_id, article.category_id, article.status_id, article.allow_comment,
                       user.pseudo as author_pseudo,
                       category.name as category_name,
                       article_status.name as status_name
                FROM article 
                INNER JOIN user ON article.author_id = user.id
                LEFT OUTER JOIN category ON article.category_id = category.id
                INNER JOIN article_status ON article.status_id = article_status.id
                WHERE article.id = :article_id';
        $result = $this->createQuery($sql, [':article_id' => $articleId]);
        $article = $result->fetch();
        $result->closeCursor();
        if($article){
            $article = $this->buildObject($article);
        }
        return $article;
    }
    
    // ok
    public function countArticles(array $parameters = []) : int
    {
        $where = "WHERE";

        extract($parameters);


        $sql = 'SELECT COUNT(article.id) FROM article 
                INNER JOIN user ON article.author_id = user.id';

        if(isset($q)){
            $sql .= ' ' . $where . ' article.content LIKE "%' . $q . '%" OR article.lede LIKE "%' . $q . '%"';
            $where = "AND";
        }

        if(isset($author)){
            $sql .= ' ' . $where . ' user.pseudo LIKE "%' . $author . '%"';
            $where = "AND";
        }

        if(isset($beforeDatetime)){
            $sql .= ' ' . $where . ' article.created_at < "' . $beforeDatetime . '"' ;
            $where = "AND";
        }

        if(isset($afterDatetime)){
            $sql .= ' ' . $where . ' article.created_at > "' . $afterDatetime . '"';
            $where = "AND";
        }

        if(!isset($allArticleStatus)){
            if(isset($published) || isset($private) || isset($standby)){
                $or = "";
                $sql .= ' ' . $where . ' (' ;
                if(isset($published)){
                    $sql .= ' article.status_id = 1';
                    $or = " OR ";
                }
                if(isset($private)){
                    $sql .= $or . ' article.status_id = 2';
                    $or = " OR ";
                }
                if(isset($standby)){
                    $sql .= $or . ' article.status_id = 3';
                }
                $sql .= ')';
                $where = "AND";
            }
        }

        if(isset($categoryId)){
            $sql .= ' ' . $where . ' article.category_id = :category_id';     
        }

        $result = $this->createQuery($sql);
        return $result->fetch(\PDO::FETCH_NUM)[0];
    }    

    /**
     *  Returns list of articles, selection options
     */
    public function getArticles(array $parameters = []) : array
    {
        $where = "WHERE";

        extract($parameters);

        $sql = 'SELECT article.id, article.created_at, article.last_modified, article.title, article.lede, article.content, article.author_id, article.category_id, article.status_id, article.allow_comment,
                       user.pseudo as author_pseudo,
                       category.name as category_name,
                       article_status.name as status_name
                FROM article 
                INNER JOIN user ON article.author_id = user.id
                LEFT OUTER JOIN category ON article.category_id = category.id
                INNER JOIN article_status ON article.status_id = article_status.id';

        if(isset($q)){
            $sql .= ' ' . $where . ' article.content LIKE "%' . $q . '%" OR article.lede LIKE "%' . $q . '%"';
            $where = "AND";
        }

        if(isset($author)){
            $sql .= ' ' . $where . ' user.pseudo LIKE "%' . $author . '%"';
            $where = "AND";
        }

        if(isset($beforeDatetime)){
            $sql .= ' ' . $where . ' article.created_at < "' . $beforeDatetime . '"' ;
            $where = "AND";
        }

        if(isset($afterDatetime)){
            $sql .= ' ' . $where . ' article.created_at > "' . $afterDatetime . '"';
            $where = "AND";
        }

        if(!isset($allArticleStatus)){
            if(isset($published) || isset($private) || isset($standby)){
                $or = "";
                $sql .= ' ' . $where . ' (' ;
                if(isset($published)){
                    $sql .= ' article.status_id = 1';
                    $or = " OR ";
                }
                if(isset($private)){
                    $sql .= $or . ' article.status_id = 2';
                    $or = " OR ";
                }
                if(isset($standby)){
                    $sql .= $or . ' article.status_id = 3';
                }
                $sql .= ')';
                $where = "AND";
            }
        }

        if(isset($categoryId)){
            $sql .= ' ' . $where . ' article.category_id = :category_id';      
        }

        if(isset($orderby)){
            $sql .= ' ORDER BY created_at ' . $orderby;
        } else {
            $sql .= ' ORDER BY created_at DESC';
        }

        if(isset($limit)){
            $sql .= " LIMIT $limit";
            if(isset($offset)){
                $sql .= " OFFSET $offset";
            }
        }

        $result = $this->createQuery($sql);
        $articles = [];
        foreach ($result as $row){
            $articleId = $row['id'];
            $articles[$articleId] = $this->buildObject($row);
        }
        $result->closeCursor();
        return $articles;
    }

    /**
     * Insert one new article
     */
    public function addArticle(Parameter $post, int $userId)
    {
        if((int)$post->get('statusId') === 3){
            $date = null;
        } else {
            $date = date('Y-m-d H:i:s');
        }
        $sql = 'INSERT INTO article (created_at, last_modified, title, lede, content, author_id, category_id, status_id, allow_comment) 
                VALUES (:created_at, null, :title, :lede, :content, :author_id, :category_id, :status_id, :allow_comment)';
        $this->createQuery($sql, ['created_at' => $date,
                                  'title' => $post->get('title'),
                                  'lede' => $post->get('lede'),
                                  'content' => $post->get('content'),                                 
                                  'author_id' => $userId,
                                  'category_id' => $post->get('categoryId'),
                                  'status_id' => $post->get('statusId'),
                                  'allow_comment' => $post->get('allowComment')]);
    }

    /**
     * Update one article
     */
    public function editArticle(Parameter $post, $articleId, $userId)
    {
        if((int)$post->get('statusId') === 3){
            $createdAt = null;
        } else {
            $createdAt = date('Y-m-d H:i:s');
        }
        $lastModified = date('Y-m-d H:i:s');
        $sql = 'UPDATE article
        SET created_at=:created_at ,last_modified=:last_modified, title=:title, lede=:lede, content=:content, author_id=:author_id, category_id=:category_id, status_id=:status_id, allow_comment=:allow_comment
        WHERE id=:article_id';
        $this->createQuery($sql,[
            'created_at' => $createdAt,
            'last_modified' => $lastModified,
            'title' => $post->get('title'),
            'lede' => $post->get('lede'),
            'content' => $post->get('content'),
            'author_id' => $userId,
            'category_id'  => $post->get('categoryId'),
            'status_id' => $post->get('statusId'),
            'allow_comment' => $post->get('allowComment'),
            'article_id' => $articleId
        ]);
    }

    public function updateArticleStatus(int $articleId,int $statusId, $date = null)
    {
        $parameters = [
            'article_id' => $articleId,
            'status_id' => $statusId
        ];
        $sql = 'UPDATE article SET status_id=:status_id';
        if(isset($date)){
            $sql .= ' , created_at=:created_at';
            $parameters['created_at'] = $date ;
        }
        $sql .= ' WHERE id=:article_id';
        $this->createQuery($sql,$parameters);
    }

    /**
     * Delete one article and its related comments
     */
    public function deleteArticle($articleId)
    {
        $sql = 'DELETE FROM comment WHERE article_id = :article_id';
        $this->createQuery($sql, ['article_id' => $articleId]);
        $sql = 'DELETE FROM article WHERE id = :article_id';
        $this->createQuery($sql, ['article_id' => $articleId]);
    }
}