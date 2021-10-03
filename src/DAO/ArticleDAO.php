<?php

namespace App\src\DAO;

use App\config\Parameter;
use App\src\model\Article;

class ArticleDAO extends DAO
{
    private function buildObject(array $row) : Article
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
    public function getArticle(int $articleId) : mixed
    {
        $this->query = $this->selectArticles()->where('a.id = :articleId');
        $result = $this->createQuery((string)$this->query, ['articleId' => $articleId]);
        $article = $result->fetch();
        $result->closeCursor();
        if($article){
            $article = $this->buildObject($article);
        }
        return $article;
    }
    
    public function countArticles(array $parameters = []) : int
    {
        $this->query = (new QueryBuilder()) ->statement('select')
                                            ->count(1)
                                            ->table('article', 'a')
                                            ->innerJoin(['u' => 'user'], 'a.author_id = u.id')
                                            ->leftJoin(['c' => 'category'], 'a.category_id = c.id')
                                            ->innerJoin(['s' => 'article_status'], 'a.status_id = s.id');


        $parameters = $this->addParameters($parameters);

        $result = $this->createQuery((string)$this->query,$parameters);
        return $result->fetch(\PDO::FETCH_NUM)[0];
    }    

    /**
     *  Returns list of articles, selection options
     */
    public function getArticles(array $parameters = []) : array
    {
        $this->query = $this->selectArticles();

        $parameters = $this->addParameters($parameters);

        $result = $this->createQuery((string)$this->query,$parameters);
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
    public function addArticle(Parameter $post) : void
    {
        $this->query = (new QueryBuilder()) ->statement('insert')
                                            ->table('article')
                                            ->insertValues(['created_at' => ':createdAt',
                                                            'last_modified' => ':lastModified',
                                                            'title' => ':title',
                                                            'lede' => ':lede',
                                                            'content' => ':content',
                                                            'author_id' => ':authorId',
                                                            'category_id' => ':categoryId',
                                                            'status_id' => ':statusId',
                                                            'allow_comment' => ':allowComment']);
        $pdo = $this->prepareQuery((string)$this->query);
        $pdo->bindValue(':createdAt', $post->get('createdAt'));
        $pdo->bindValue(':lastModified', null);
        $pdo->bindValue(':title', $post->get('title'));
        $pdo->bindValue(':lede', $post->get('lede'));
        $pdo->bindValue(':content', $post->get('content'));
        $pdo->bindValue(':authorId', (int)$post->get('authorId'));
        $pdo->bindValue(':categoryId', (int)$post->get('categoryId'));
        $pdo->bindValue(':statusId', (int)$post->get('statusId'));
        $pdo->bindValue(':allowComment', (int)$post->get('allowComment'));
        $pdo->execute();                      
    }

    /**
     * Update one article
     */
    public function editArticle(Parameter $post,int $articleId) : void
    {
        $this->query = (new QueryBuilder()) ->statement('update')
                                            ->table('article')
                                            ->set('last_modified = :lastModified',
                                                    'title = :title',
                                                    'lede=:lede',
                                                    'content = :content',
                                                    'author_id = :authorId',
                                                    'category_id = :categoryId',
                                                    'status_id = :statusId',
                                                    'allow_comment = :allowComment');
        if($post->get('createdAt')){
            $this->query->set('created_at = :createdAt');
        }
        $this->query->where('id = :articleId');

        $pdo = $this->prepareQuery((string)$this->query);

        if($post->get('createdAt')){
            $pdo->bindValue(':createdAt', $post->get('createdAt'));
        }        
        $pdo->bindValue(':lastModified', $post->get('lastModified'));
        $pdo->bindValue(':title', $post->get('title'));
        $pdo->bindValue(':lede', $post->get('lede'));
        $pdo->bindValue(':content', $post->get('content'));
        $pdo->bindValue(':authorId', (int)$post->get('authorId'));
        $pdo->bindValue(':categoryId', (int)$post->get('categoryId'));
        $pdo->bindValue(':statusId', (int)$post->get('statusId'));
        $pdo->bindValue(':allowComment', (int)$post->get('allowComment'));
        $pdo->bindValue(':articleId', $articleId);
        $pdo->execute();
    }

    public function updateArticleStatus(array $parameters) : void
    {
        $this->query = (new QueryBuilder()) ->statement('update')
                                            ->table('article')
                                            ->set('status_id = :statusId')
                                            ->where('id = :articleId');                                        
        if(isset($parameters['createdAt'])){
            $this->query->set('created_at = :createdAt');
        }
        $this->createQuery((string)$this->query,$parameters);
    }

    /**
     * Delete one article and its related comments
     */
    public function deleteArticle(int $articleId) : void
    {
        $parameters['articleId'] = $articleId;
        $this->query = (new QueryBuilder()) ->statement('delete')
                                            ->table('comment')
                                            ->where('article_id = :articleId');
        $this->createQuery((string)$this->query,$parameters);
        $this->query = (new QueryBuilder()) ->statement('delete')
                                            ->table('article')
                                            ->where('id = :articleId');
        $this->createQuery((string)$this->query,$parameters);
    }

    private function addParameters(array $parameters = []) : array
    {
        if(isset($parameters['q'])){
            $this->query->subWhere('a.content LIKE "%' . htmlentities($parameters['q']) . '%" OR a.lede LIKE "%' . htmlentities($parameters['q']) . '%" OR a.title LIKE "%' . htmlentities($parameters['q']) . '%" OR u.pseudo LIKE "%' . htmlentities($parameters['q']) . '%"');
            unset($parameters['q']);
        }

        if(isset($parameters['authorId'])){
            $this->query->where('a.author_id = :authorId');
        }

        if(isset($parameters['categoryId'])){
            $this->query->where('a.category_id = :categoryId');
        }

        if(isset($parameters['published']) || isset($parameters['private']) || isset($parameters['standby'])){
            $conditions = [];
            if(isset($parameters['published'])){
                $conditions[] = 's.name = :published';
                $parameters['published'] = 'published';
            }
            if(isset($parameters['private'])){
                $conditions[] = 's.name = :private';
                $parameters['private'] = 'private';
            }
            if(isset($parameters['standby'])){
                $conditions[] = 's.name = :standby';
                $parameters['standby'] = 'standby';
            }
            $this->query->subWhere(join(' OR ', $conditions));
        }



        // Common

        if(isset($parameters['beforeDatetime'])){
            $this->query->where('a.created_at < :beforeDatetime');
        }
        
        if(isset($parameters['afterDatetime'])){
            $this->query->where('a.created_at > :afterDatetime');
        }

        if(isset($parameters['orderBy'])){
            $this->query->orderBy($parameters['orderBy']);
            unset($parameters['orderBy']);
        } else {
            $this->query->orderBy(['column'=>'a.created_at','order'=>'DESC']);
            unset($parameters['orderBy']);
        }

        if(isset($parameters['limit'])){
            $this->query->limit($parameters['limit']);
            unset($parameters['limit']);
            if(isset($parameters['offset'])){
                $this->query->offset($parameters['offset']);
                unset($parameters['offset']);
            }
        }

        return $parameters;
    }

    private function selectArticles() : QueryBuilder
    {
        return (new QueryBuilder()) ->statement('select')
                                    ->select('a.id', 'a.created_at', 'a.last_modified', 'a.title', 'a.lede', 'a.content', 'a.author_id', 'a.category_id', 'a.status_id', 'a.allow_comment',
                                    'u.pseudo as author_pseudo',
                                    'c.name as category_name',
                                    's.name as status_name')
                                    ->table('article', 'a')
                                    ->innerJoin(['u' => 'user'], 'a.author_id = u.id')
                                    ->leftJoin(['c' => 'category'], 'a.category_id = c.id')
                                    ->innerJoin(['s' => 'article_status'], 'a.status_id = s.id');
    }
}