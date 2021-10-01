<?php

namespace App\src\controller\backcontroller;

use App\config\Parameter;
use App\src\controller\Controller;
use App\src\utils\URL;

class BackAdminController extends BackController
{
    private $limitArray = [10,20,30,40,50,75,100];
    private $defaultLimit = 20;
    private $parameters;
    private $data;

    public function administration()
    {
        $this->checkAdmin();
        $this->setPreviousURI();
        $data['comments'] = $this->commentDAO->getComments([
            'limit' => 10,
            'validated' => "pending",
            'orderby' => 'DESC'
        ]);
        $data['articles'] = $this->articleDAO->getArticles([
            'limit' => 10,
            'orderby' => 'DESC'
        ]);
        $data['users'] = $this->userDAO->getUsers([
            'limit' => 10,
            'orderby' => 'DESC'
        ]);

        $data['title'] = 'Administration';
        return $this->view->renderTwig('administration', $data);
    }

    public function adminComments(Parameter $get)
    {
        $this->checkAdmin();
        $this->setPreviousURI();
        $limitArray = [10,20,30,40,50,75,100];
        $page = 1;
        $parameters['page'] = &$page;
        $limit = 20;
        $parameters = [];
        // En attendant mieux
        $parameters['orderby'] = 'DESC';
        $author = null;
        $articleId = null;
        $afterDatetime = null;
        $beforeDatetime = null;
        $validated = null;
        $previousPageURL = null;
        $nextPageURL = null;

        if(!empty($get->get('q'))){
            $parameters['q'] = htmlentities($get->get('q'));
        }

        if(!empty($get->get('userId'))){
            $parameters['userId'] = htmlentities($get->get('userId'));
        }

        if(!empty($get->get('articleId'))){
            $parameters['articleId'] = (int)$get->get('articleId');
        }

        if(!empty($get->get('afterDatetime'))){
            $parameters['afterDatetime'] =  htmlentities($get->get('afterDatetime'));
        }

        if(!empty($get->get('beforeDatetime'))){
            $parameters['beforeDatetime'] =  htmlentities($get->get('beforeDatetime'));
        }

        if(!empty($get->get('validated'))){
            if($get->get('validated') === "validated" || $get->get('validated') === "pending"){
                $parameters['validated'] = $get->get('validated');                
            }
        }

        if((int)$get->get('page') > 1){
            $page = $get->get('page');
        }

        if(in_array((int)$get->get('limit'),$limitArray)){
            $limit = $get->get('limit');
        }
        $parameters['limit'] = $limit;

        $commentsCount = $this->commentDAO->countComments($parameters);

        $pages = ceil($commentsCount/$limit);
        if($page <= $pages && $page > 1){
            $parameters['offset'] = $limit*($page - 1);         
        } else {
            $page = 1;
            $parameters['offset'] = 0;
        }

        $comments = $this->commentDAO->getComments($parameters);

        $pageCommentsCount = count($comments);

        if($page > 1){
            $previousPageURL = URL::mergeOn($this->get->all(),['page' => $page - 1]) . "#resultsTable";
        }

        if($page < $pages && $pages !== 1){
            $nextPageURL = URL::mergeOn($this->get->all(),['page' => $page + 1]) . "#resultsTable";
        }

        // Articles list for <select>
        $articlesList = $this->articleDAO->getArticles();
        $i = 0;
        foreach($articlesList as $article){  
            $data['articles'][$i]['value'] = $article->getId();
            $data['articles'][$i]['name'] = $article->getTitle();
            $i ++;
        }

        // Users list for <select>
        $users = $this->userDAO->getUsers();
        $i = 0;
        foreach($users as $user){  
            $data['users'][$i]['value'] = $user->getId();
            $data['users'][$i]['name'] = $user->getPseudo();
            $i ++;
        }

        // Limits list for <select>
        $i = 0;
        foreach($limitArray as $key => $limit){  
            $data['limit'][$i]['value'] = (string)$limit;
            $data['limit'][$i]['name'] = (string)$limit;
            $i ++;
        }

        return $this->view->renderTwig('adminComments', [
            'title' => 'Administration des commentaires',
            'get' => $get,
            'comments' => $comments,
            'articles' => $data['articles'],
            'limits' => $data['limit'],
            'users' => $data['users'],
            'page' => $page,
            'pages' => $pages,
            'totalCommentsCount' => $commentsCount,
            'pageCommentsCount' => $pageCommentsCount,
            'previousPageURL' => $previousPageURL,
            'nextPageURL' => $nextPageURL
        ]);             
    }

    public function adminArticles(Parameter $get)
    {
        $this->checkAdmin();
        $this->setPreviousURI();
        $limitArray = [10,20,30,40,50];
        $page = 1;
        $parameters['page'] = &$page;
        $limit = 20;
        $parameters = [];

        if(!empty($get->get('q'))){
            $parameters['q'] = htmlentities($get->get('q'));
        }

        if(!empty($get->get('authorId'))){
            $parameters['authorId'] = htmlentities($get->get('authorId'));
        }

        if(!empty($get->get('all'))){
            $parameters['all'] = 1;
        } else {
            if(!empty($get->get('published'))){
                $parameters['published'] = 1;
            }
            if(!empty($get->get('private'))){
                $parameters['private'] = 1;
            }
            if(!empty($get->get('standby'))){
                $parameters['standby'] = 1;
            }
        }

        if(!empty($get->get('afterDatetime'))){
            $parameters['afterDatetime'] =  htmlentities($get->get('afterDatetime'));
        }

        if(!empty($get->get('beforeDatetime'))){
            $parameters['beforeDatetime'] =  htmlentities($get->get('beforeDatetime'));
        }

        if((int)$get->get('page') > 1){
            $page = $get->get('page');
        }

        $articlesCount = (int) $this->articleDAO->countArticles($parameters);

        if(in_array((int)$get->get('limit'),$limitArray)){
            $limit = $get->get('limit');
        }
        $parameters['limit'] = $limit; 

        $pages = ceil($articlesCount/$limit);
        if($page <= $pages && $page > 1){
            $parameters['offset'] = $limit*($page - 1);     
        } else {
            $page = 1;
            $parameters['offset'] = 0;
        }

        $articles = $this->articleDAO->getArticles($parameters);

        $pageArticlesCount = count($articles);

        $previousPageURL = null;
        $nextPageURL = null;

        if($page > 1){
            $previousPageURL = URL::mergeOn($this->get->all(),['page' => $page - 1]);
        }

        if($page < $pages && $pages !== 1){
            $nextPageURL = URL::mergeOn($this->get->all(),['page' => $page + 1]);
        }

        // Users list for <select>
        $users = $this->userDAO->getUsers();
        $i = 0;
        foreach($users as $user){  
            $data['users'][$i]['value'] = $user->getId();
            $data['users'][$i]['name'] = $user->getPseudo();
            $i ++;
        }

        // Limits list for <select>
        $i = 0;
        foreach($limitArray as $key => $limit){  
            $data['limit'][$i]['value'] = (string)$limit;
            $data['limit'][$i]['name'] = (string)$limit;
            $i ++;
        }

        return $this->view->renderTwig('adminArticles', [
            'title' => 'Administration des articles',
            'get' => $get,
            'articles' => $articles,
            'users' => $data['users'],
            'limits' => $data['limit'],
            'page' => $page,
            'pages' => $pages,
            'totalArticlesCount' => $articlesCount,
            'pageArticlesCount' => $pageArticlesCount,
            'previousPageURL' => $previousPageURL,
            'nextPageURL' => $nextPageURL
        ]);
    }

    public function adminUsers(Parameter $get)
    {
        $this->checkAdmin();
        $this->setPreviousURI();
        
        $this->parameters = $this->getCleanParameters($get->all(),'user');
    
        $this->data['page'] = isset($this->parameters['page']) && $this->parameters['page'] > 1 ? $this->parameters['page'] : 1;
        unset($this->parameters['page']);
        $this->data['title'] = 'Administration des utilisateurs';
        $this->data['get'] = $get;

        if(in_array((int)$get->get('limit'),$this->limitArray)){
            $this->parameters['limit'] = $get->get('limit');
        } else {
            $this->parameters['limit'] = $this->defaultLimit ;
        }

        if(isset($this->parameters['allUserStatus'])){
            unset($this->parameters['online']);
            unset($this->parameters['offline']);
            unset($this->parameters['banned']);
            unset($this->parameters['allUserStatus']);
        }

        if(isset($this->parameters['allUserRoles'])){
            unset($this->parameters['admin']);
            unset($this->parameters['moderator']);
            unset($this->parameters['editor']);
            unset($this->parameters['user']);
            unset($this->parameters['allUserRoles']);
        }

        $this->data['totalUsersCount'] = (int) $this->userDAO->countUsers($this->parameters);

        $this->data['pages'] = ceil($this->data['totalUsersCount']/$this->parameters['limit']);

        // checks if given parameters['page'] is compatible with offset
        if($this->data['page'] <= $this->data['pages'] && $this->data['page'] > 1){
            $this->parameters['offset'] = $this->parameters['limit']*($this->data['page'] - 1);     
        } else {
            $this->data['page'] = 1;
            $this->parameters['offset'] = 0;
        }

        $this->data['users'] = $this->userDAO->getUsers($this->parameters);

        $this->data['pageUsersCount'] = count($this->data['users']);

        $this->data['previousPageURL'] = $this->data['page'] > 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] - 1]) . "#resultsTable" : null;
        $this->data['nextPageURL'] = $this->data['page'] < $this->data['pages'] && $this->data['pages'] !== 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] + 1]) . "#resultsTable" : null;

        $i = 0;
        foreach($this->limitArray as $key => $limit){  
            $this->data['limits'][$i]['value'] = (string)$limit;
            $this->data['limits'][$i]['name'] = (string)$limit;
            $i ++;
        }
        
        return $this->view->renderTwig('adminUsers', $this->data);
    }
}