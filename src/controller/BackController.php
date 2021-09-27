<?php

namespace App\src\controller;

use App\config\Parameter;
use App\config\HTTP;
use App\src\utils\URL;
use App\src\utils\Text;


class BackController extends Controller
{
    // ok twig
    private function checkAdmin()
    {
        $this->checkLoggedIn();

        if(!($this->session->get('role') === 'admin')){
            $this->session->addMessage('danger', 'Vous n\'avez pas le droit d\'accéder à cette page');
            HTTP::redirect('?');
        }
    }

    // ARTICLES
    // ok twig
    public function addArticle(Parameter $post)
    {
        $this->checkAdmin();

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Article');
            if(!$errors){
                $post->set('lede', Text::HtmlToMarkdown($post->get('lede')));
                $post->set('content',Text::HtmlToMarkdown($post->get('content')));
                $this->articleDAO->addArticle($post);
                $this->session->addMessage('success', 'Le nouvel article a bien été ajouté');
                HTTP::redirect('?route=articles');
            }
            $data['post'] = $post;
            $data['errors'] = $errors;
        }

        $users = $this->userDAO->getUsers();
        $i = 0;
        foreach($users as $user){  
            $data['users'][$i]['value'] = $user->getId();
            $data['users'][$i]['name'] = $user->getPseudo();
            $i ++;
        }
        
        $categories = $this->categoryDAO->getCategories();
        $i = 0;
        foreach($categories as $category){
            $data['categories'][$i]['value'] = $category->getId();
            $data['categories'][$i]['name'] = $category->getName();
            $i ++;
        }

        $data['title'] = 'Nouvel article';

        return $this->view->renderTwig('editArticle', $data);
    }

    // ok twig
    public function editArticle(Parameter $post, $articleId)
    {
        $this->checkAdmin();

        // Users list for <select>
        $users = $this->userDAO->getUsers();
        $i = 0;
        foreach($users as $user){  
            $data['users'][$i]['value'] = $user->getId();
            $data['users'][$i]['name'] = $user->getPseudo();
            $i ++;
        }
        
        // Categories list for <select>
        $categories = $this->categoryDAO->getCategories();
        $i = 0;
        foreach($categories as $category){
            $data['categories'][$i]['value'] = $category->getId();
            $data['categories'][$i]['name'] = $category->getName();
            $i ++;
        }

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Article');
            if(!$errors){
                $post->set('lede', Text::HtmlToMarkdown($post->get('lede')));
                $post->set('content',Text::HtmlToMarkdown($post->get('content')));
                if(!$post->get('categoryId')){
                    $post->set('categoryId',"1");
                }
                $this->articleDAO->editArticle($post, $articleId);
                $this->session->addMessage('success', 'L\'article a bien été modifié');   
                HTTP::redirect('?route=article&articleId=' . $articleId);
            }
            $data['title'] = 'Modification : ' . $post->get('Title');
            $data['post'] = $post;
            $data['errors'] = $errors;
            return $this->view->renderTwig('editArticle', $data);
        }

        $article = $this->articleDAO->getArticle($articleId);

        $post->set('id', $article->getId());
        $post->set('title', $article->getTitle());
        $post->set('categoryId', $article->getCategoryId());
        $post->set('categoryName', $article->getCategoryName());
        $post->set('lede', $article->getLede());
        $post->set('content', $article->getContent());
        $post->set('authorPseudo', $article->getAuthorPseudo());
        $post->set('authorId', $article->getAuthorId());
        $post->set('allowComment', $article->getAllowComment());
        $post->set('statusId', $article->getStatusId());

        $data['title'] = 'Modification : ' . $article->getTitle();
        $data['post'] = $post;

        return $this->view->renderTwig('editArticle', $data);
    }

    // ok twig
    public function updateArticleStatus(int $articleId,int $statusId)
    {
        $this->checkAdmin();

        $date = null;
        if($statusId === 1 || $statusId === 2){
            $article = $this->articleDAO->getArticle($articleId);
            if((int)$article->getStatusId() === 3 && $article->getCreatedAt() === null){
                $date = date('Y-m-d H:i:s');
            }
        }
        $this->articleDAO->updateArticleStatus($articleId, $statusId, $date);
        $this->session->addMessage('success', 'Le statut de l\'article a bien été mis à jour');
        HTTP::dynamicRedirect('?route=adminArticles',$this->session);
    } 
    
    // ok twig
    public function deleteArticle($articleId)
    {
        $this->checkAdmin();

        $this->articleDAO->deleteArticle($articleId);
        $this->session->addMessage('success', 'L\' article a bien été supprimé');
        HTTP::dynamicRedirect('?route=adminArticles',$this->session);
    }

    // COMMENTS

    // ok twig
    public function viewSingleComment(int $commentId)
    {
        $this->checkAdmin();

        $comment = $this->commentDAO->getComment($commentId);
        if($comment){
            $data['comment'] = $comment;
            $data['title'] = 'Commentaire de : ' . $comment->getUserPseudo();
            return $this->view->renderTwig('singleComment', $data);
        }
        $this->session->addMessage('danger', 'Le commentaire recherché n\'existe pas / plus');
        HTTP::dynamicRedirect('?route=adminComments',$this->session);  
    }

    // ok twig
    public function adminEditComment(Parameter $post, $commentId)
    {
        $this->checkAdmin();
        
        $comment = $this->commentDAO->getComment($commentId);

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Comment');
            if(!$errors){
                $validated = 1;
                $this->commentDAO->editComment(htmlspecialchars($post->get('comment')), $commentId, $validated);
                $this->session->addMessage('success', 'Le commentaire a bien été modifié et publié');  
                HTTP::dynamicRedirect('?route=adminComments',$this->session);                
            }
            $data['errors'] = $errors ;
        } else {
            $post->set('comment', $comment->getContent());
        }

        $data['title'] = 'Modifier le commentaire';
        $data['post'] = $post;

        return $this->view->renderTwig('adminEditComment', $data);
    }

    // ok twig
    public function updateCommentValidation(int $commentId, int $validated)
    {
        $this->checkAdmin();
        
        if($validated === 1 || $validated === 0){
            $this->commentDAO->updateCommentValidation($commentId, $validated);
            $message = $validated ? 'validé' : 'suspendu';
            $this->session->addMessage('success', 'Le commentaire a bien été ' . $message);            
        }
        HTTP::dynamicRedirect('?route=adminComments',$this->session);
    }

    // ok twig
    public function deleteComment(int $commentId)
    {
        $this->checkAdmin();

        if($this->commentDAO->getComment($commentId)){
            $this->commentDAO->deleteComment($commentId);
            $this->session->addMessage('success', 'Le commentaire a bien été supprimé');
        } else {
            $this->session->addMessage('danger', 'Le commentaire à supprimer n\'existe pas / plus');
        }
        HTTP::dynamicRedirect('?route=adminComments',$this->session);
    }

    // USERS

    // ok twig
    public function updateUserRole(int $userId, int $roleId)
    {
        $this->checkAdmin();
        $roleArray = [1,2,3,4];
        $user = $this->userDAO->getUser($userId);
        if($user){
            if(((int)$user->getRoleId() !== 1) && ((int)$user->getRoleId() !== $roleId) && in_array($roleId, $roleArray)){

                $this->userDAO->updateUserRole($userId,$roleId);

                if($roleId === 1){
                    $roleName = "Administrateur";
                } elseif($roleId === 2){
                    $roleName = "Utilisateur";
                } elseif($roleId === 3){
                    $roleName = "Éditeur";
                } elseif($roleId === 4){
                    $roleName = "Modérateur";
                }
                $this->session->addMessage('success', 'Le rôle de ' . $user->getPseudo() . ' a bien été mis à jour, nouveau rôle : ' . $roleName);
            }            
        }
        HTTP::dynamicRedirect('?route=adminUsers',$this->session);
    }

    // ok twig
    public function updateUserStatus(int $userId, int $statusId)
    {
        $this->checkAdmin();
        $statusArray = [1,3];
        $user = $this->userDAO->getUser($userId);

        if($user){
            if(((int)$user->getRoleId() !== 1) && in_array($statusId, $statusArray)){

                $this->userDAO->updateUserStatus($userId,$statusId);

                if($statusId === 1){
                    $statusName= "Relaxé";
                } elseif($statusId === 3){
                    $statusName = "Banni";
                } else{
                    $statusName = "???";
                }
                $this->session->addMessage('success', $user->getPseudo() . ' a bien été ' . $statusName);
            }            
        }
        HTTP::dynamicRedirect('?route=adminUsers',$this->session);
    }

    // GLOBAL ADMIN
    // ok twig
    public function administration()
    {
        $this->checkAdmin();

        $this->session->set('previousURL', $_SERVER['REQUEST_URI']);

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

    // ok twig
    public function adminComments(Parameter $get)
    {
        $this->checkAdmin();

        $this->session->set('previousURL', $_SERVER['REQUEST_URI']);

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
            $previousPageURL = URL::mergeOn($_GET, ['page' => $page - 1]) . "#resultsTable";
        }

        if($page < $pages && $pages !== 1){
            $nextPageURL = URL::mergeOn($_GET, ['page' => $page + 1]) . "#resultsTable";
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
        $is = 0;
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

    // ok twig
    public function adminArticles(Parameter $get)
    {
        $this->checkAdmin();

        $this->session->set('previousURL', $_SERVER['REQUEST_URI']);

        $limitArray = [10,20,30,40,50];
        $page = 1;
        $parameters['page'] = &$page;
        $limit = 20;
        $parameters = [];

        if(!empty($get->get('q'))){
            $parameters['q'] = htmlentities($get->get('q'));
        }

        if(!empty($get->get('author'))){
            $parameters['author'] = htmlentities($get->get('author'));
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
            $previousPageURL = URL::mergeOn($_GET, ['page' => $page - 1]);
        }

        if($page < $pages && $pages !== 1){
            $nextPageURL = URL::mergeOn($_GET, ['page' => $page + 1]);
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
        $is = 0;
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

    // ok
    public function adminUsers(Parameter $get)
    {
        $this->checkAdmin();

        $this->session->set('previousURL', $_SERVER['REQUEST_URI']);

        $limitArray = [10,20,30,40,50,75,100];
        $page = 1;
        $parameters['page'] = &$page;
        $limit = 20;
        $parameters = [];

        if(!empty($get->get('q'))){
            $parameters['q'] = htmlentities($get->get('q'));
        }

        if(!empty($get->get('scoreHigherThan'))){
            $parameters['scoreHigherThan'] =  (int)$get->get('scoreHigherThan');
        }

        if(!empty($get->get('scoreLowerThan'))){
            $parameters['scoreLowerThan'] =  (int)$get->get('scoreLowerThan');
        }

        if(!empty($get->get('allUserStatus'))){
            $parameters['allUserStatus'] = 1;
        } else {
            if(!empty($get->get('online'))){
                $parameters['online'] = 1;
            }
            if(!empty($get->get('offline'))){
                $parameters['offline'] = 1;
            }
            if(!empty($get->get('banned'))){
                $parameters['banned'] = 1;
            }
        }

        if(!empty($get->get('allUserRoles'))){
            $parameters['allUserRoles'] = 1;
        } else {
            if(!empty($get->get('admin'))){
                $parameters['admin'] = 1;
            }
            if(!empty($get->get('moderator'))){
                $parameters['moderator'] = 1;
            }
            if(!empty($get->get('editor'))){
                $parameters['editor'] = 1;
            }
            if(!empty($get->get('user'))){
                $parameters['user'] = 1;
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

        $usersCount = (int) $this->userDAO->countUsers($parameters);

        if(in_array((int)$get->get('limit'),$limitArray)){
            $limit = $get->get('limit');
        }

        $parameters['limit'] = $limit; 

        $pages = ceil($usersCount/$limit);
        if($page <= $pages && $page > 1){
            $parameters['offset'] = $limit*($page - 1);     
        } else {
            $page = 1;
            $parameters['offset'] = 0;
        }

        $users = $this->userDAO->getUsers($parameters);

        $pageUsersCount = count($users);

        $previousPageURL = null;
        $nextPageURL = null;

        if($page > 1){
            $previousPageURL = URL::mergeOn($_GET, ['page' => $page - 1]) . "#resultsTable";
        }

        if($page < $pages && $pages !== 1){
            $nextPageURL = URL::mergeOn($_GET, ['page' => $page + 1]) . "#resultsTable";
        }

        // Limits list for <select>
        $i = 0;
        foreach($limitArray as $key => $limit){  
            $data['limit'][$i]['value'] = (string)$limit;
            $data['limit'][$i]['name'] = (string)$limit;
            $i ++;
        }

        $data['users'] = $users;
        
        return $this->view->renderTwig('adminUsers', [
            'title' => 'Administration des utilisateurs',
            'get' => $get,
            'users' => $data['users'],
            'limits' => $data['limit'],
            'users' => $users,
            'page' => $page,
            'pages' => $pages,
            'totalUsersCount' => $usersCount,
            'pageUsersCount' => $pageUsersCount,
            'previousPageURL' => $previousPageURL,
            'nextPageURL' => $nextPageURL
        ]);
    }
}