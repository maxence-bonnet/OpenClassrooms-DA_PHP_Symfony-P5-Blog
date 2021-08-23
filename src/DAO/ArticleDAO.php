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
            return $this->buildObject($article);
        }
    }

    /**
     *  Returns list of articles, selection options : article statuts, order, limit, limit offset
     */
    public function getArticles($options = null)
    {
        $statusId = null;
        $categoryId = null;
        $orderby = null;
        $limit = null;
        $offset = null;
        if($options){
            extract($options);
        }
        $parameters = null;
        $sql = 'SELECT article.id, article.created_at, article.last_modified, article.title, article.lede, article.content, article.author_id, article.category_id, article.status_id, article.allow_comment,
                       user.pseudo as author_pseudo,
                       category.name as category_name,
                       article_status.name as status_name
                FROM article 
                INNER JOIN user ON article.author_id = user.id
                LEFT OUTER JOIN category ON article.category_id = category.id
                INNER JOIN article_status ON article.status_id = article_status.id';
                
        if($statusId){
            $sql .= " WHERE article.status_id = :status_id";
            $parameters['status_id'] = $statusId;
            if($categoryId){
                $sql .= " AND article.category_id = :category_id";
                $parameters['category_id'] = $categoryId;            
            }
        }
        if($orderby){
            $sql .= " ORDER BY article.id $orderby";
        }
        if($limit){
            $sql .= " LIMIT $limit";
            if($offset){
                $sql .= " OFFSET $offset";
            }
        }

        $result = $this->createQuery($sql,$parameters);
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
        $sql = 'INSERT INTO article (created_at, last_modified, title, lede, content, author_id, category_id, status_id, allow_comment) 
                VALUES (NOW(), null, :title, :lede, :content, :author_id, :category_id, :status_id, :allow_comment)';
        $this->createQuery($sql, ['title' => $post->get('title'),
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
        $sql = 'UPDATE article
        SET last_modified=NOW(), title=:title, lede=:lede, content=:content, author_id=:author_id, category_id=:category_id, status_id=:status_id, allow_comment=:allow_comment
        WHERE id=:article_id';
        $this->createQuery($sql, [
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