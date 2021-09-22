<?php

namespace App\src\controller;

use App\config\Parameter;
use App\config\HTTP;
use App\src\utils\URL;

class BackController extends Controller
{
    // ok
    private function checkAdmin()
    {
        $this->checkLoggedIn();

        if(!($this->session->get('role') === 'admin')) {
            $this->session->set('adminOnly', '<div class="alert alert-danger">Vous n\'avez pas le droit d\'accéder à cette page</div>');
            HTTP::redirect('?');
        }
    }

    // ARTICLES
    // ok
    public function addArticle(Parameter $post)
    {
        $this->checkAdmin();

        if ($post->get('submit')) {
            $errors = $this->validation->validate($post, 'Article');
            if (!$errors) {
                $this->articleDAO->addArticle($post, $this->session->get('id'));
                $this->session->set('addedArticle', '<div class="alert alert-success">Le nouvel article a bien été ajouté</div>');
                HTTP::redirect('?route=articles');
            }
            return $this->view->render('edit_article', [
                'post' => $post,
                'errors' => $errors
            ]);
        }
        return $this->view->render('edit_article');
    }

    // ok
    public function editArticle(Parameter $post, $articleId)
    {
        $this->checkAdmin();

        if ($post->get('submit')) {
            $errors = $this->validation->validate($post, 'Article');
            if (!$errors) {
                $this->articleDAO->editArticle($post, $articleId, $post->get('authorId')); // à l'avenir : $this->session->get('id') plutôt que $post->get('authorId')
                $this->session->set('editedArticle', '<div class="alert alert-success">L\'article a bien été modifié</div>');   
                HTTP::redirect('?route=article&articleId=' . $articleId);
            }
            return $this->view->render('edit_article', [
                'post' => $post,
                'errors' => $errors
            ]);
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
        $post->set('statusId', $article->getstatusId());
        return $this->view->render('edit_article', [
            'post' => $post
        ]);
    }

    // ok
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
        $this->session->set('updatedArticleStatus', '<div class="alert alert-success">Le statut de l\'article a bien été mis à jour</div>');
        HTTP::dynamicRedirect('?route=adminArticles',$this->session);
    } 
    
    // ok
    public function deleteArticle($articleId)
    {
        $this->checkAdmin();

        $this->articleDAO->deleteArticle($articleId);
        $this->session->set('deletedArticle', '<div class="alert alert-success">L\' article a bien été supprimé</div>');
        HTTP::dynamicRedirect('?route=articles',$this->session);
    }

    // COMMENTS
    // ok
    public function viewSingleComment(int $commentId)
    {
        $this->checkAdmin();

        $comment = $this->commentDAO->getComment($commentId);
        if($comment){
            return $this->view->render('singleComment', ['comment' => $comment]);
        }
        $this->session->set('unfoundComment', '<div class="alert alert-danger">Le commentaire recherché n\'existe pas / plus</div>');
        HTTP::dynamicRedirect('?route=adminComments',$this->session);  
    }

    // ok
    public function adminEditComment(Parameter $post, $commentId)
    {
        $this->checkAdmin();
        
        $comment = $this->commentDAO->getComment($commentId);
        $articleId = $comment->getArticleId();
        if ($post->get('submit')) {
            $errors = $this->validation->validate($post, 'Comment');
            if (!$errors) {
                $validated = 1;
                $this->commentDAO->editComment($post, $commentId, $validated);
                $this->session->set('adminEditedComment', '<div class="alert alert-success">Le commentaire a bien été modifié et publié</div>');  
                HTTP::dynamicRedirect('?route=adminComments',$this->session);                
            }
            return $this->view->render('adminEditComment', ['comment' => $comment]);
        }   
        $post->set('id', $comment->getId());
        $post->set('content', $comment->getContent());
        return $this->view->render('adminEditComment', [
            'comment' => $comment,
            'post' => $post
        ]);
    }

    // ok
    public function updateCommentValidation(int $commentId, int $validation)
    {
        $this->checkAdmin();

        if($validation === 1 ||$validation === 0){
            $this->commentDAO->updateCommentValidation($commentId,$validation);
            $message = $validation ? 'validé' : 'suspendu';
            $this->session->set('updatedCommentValidation', '<div class="alert alert-success">Le commentaire a bien été ' . $message . '</div>');            
        }
        HTTP::dynamicRedirect('?route=adminComments',$this->session);
    }

    // ok
    public function deleteComment(int $commentId)
    {
        $this->checkAdmin();

        if($this->commentDAO->getComment($commentId)){
            $this->commentDAO->deleteComment($commentId);
            $this->session->set('deletedComment', '<div class="alert alert-success">Le commentaire a bien été supprimé</div>');
        } else {
            $this->session->set('unfoundComment', '<div class="alert alert-danger">Le commentaire à supprimer n\'existe pas / plus</div>');
        }
        HTTP::dynamicRedirect('?route=adminComments',$this->session);
    }

    // USERS
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
                $this->session->set('updatedUserRole', '<div class="alert alert-success"> Le rôle de ' . $user->getPseudo() . ' a bien été mis à jour, nouveau rôle : ' . $roleName . '</div>');
            }            
        }
        HTTP::dynamicRedirect('?route=adminUsers',$this->session);
    }

    public function updateUserStatus(int $userId, int $statusId)
    {
        $this->checkAdmin();
        $statusArray = [1,2,3,4];
        $user = $this->userDAO->getUser($userId);
        if($user){
            if(((int)$user->getRoleId() !== 1) && in_array($statusId, $statusArray)){
                $this->userDAO->updateUserStatus($userId,$statusId);
                if($statusId === 1){
                    $statusName= "Relaxé";
                } elseif($statusId === 2){
                    $statusName = "???";
                } elseif($statusId === 3){
                    $statusName = "Banni";
                } else{
                    $statusName = "???";
                }
                $this->session->set('updatedUserStatus', '<div class="alert alert-success">' . $user->getPseudo() . ' a bien été ' . $statusName . '</div>');
            }            
        }
        HTTP::dynamicRedirect('?route=adminUsers',$this->session);
    }

    // GLOBAL ADMIN
    // ok
    public function administration()
    {
        $this->checkAdmin();

        $this->session->set('previousURL', $_SERVER['REQUEST_URI']);

        $comments = $this->commentDAO->getComments([
            'limit' => 10,
            'validated' => "pending",
            'orderby' => 'DESC'
        ]);
        $articles = $this->articleDAO->getArticles([
            'limit' => 10,
            'orderby' => 'DESC'
        ]);
        $users = $this->userDAO->getUsers([
            'limit' => 10,
            'orderby' => 'DESC'
        ]);
        return $this->view->render('administration', [
            'articles' => $articles,
            'comments' => $comments,
            'users' => $users
        ]);
    }

    // ok
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
        $previousPageUrl = null;
        $nextPageUrl = null;

        if(!empty($get->get('q'))){
            $parameters['q'] = htmlentities($get->get('q'));
        }

        if(!empty($get->get('author'))){
            $parameters['author'] = htmlentities($get->get('author'));
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
            $previousPageUrl = URL::mergeOn($_GET, ['page' => $page - 1]);
        }

        if($page < $pages && $pages !== 1){
            $nextPageUrl = URL::mergeOn($_GET, ['page' => $page + 1]);
        }

        $articlesList = $this->articleDAO->getArticles();

        return $this->view->render('adminComments', [
            'comments' => $comments,
            'articlesList' => $articlesList,
            'page' => $page,
            'pages' => $pages,
            'totalCommentsCount' => $commentsCount,
            'pageCommentsCount' => $pageCommentsCount,
            'previousPageUrl' => $previousPageUrl,
            'nextPageUrl' => $nextPageUrl
        ]);             
    }

    // ok
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

        if(!empty($get->get('allArticleStatus'))){
            $parameters['allArticleStatus'] = 1;
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

        $previousPageUrl = null;
        $nextPageUrl = null;

        if($page > 1){
            $previousPageUrl = URL::mergeOn($_GET, ['page' => $page - 1]);
        }

        if($page < $pages && $pages !== 1){
            $nextPageUrl = URL::mergeOn($_GET, ['page' => $page + 1]);
        }

        return $this->view->render('adminArticles', [
            'articles' => $articles,
            'page' => $page,
            'pages' => $pages,
            'totalArticlesCount' => $articlesCount,
            'pageArticlesCount' => $pageArticlesCount,
            'previousPageUrl' => $previousPageUrl,
            'nextPageUrl' => $nextPageUrl
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
            $parameters['allUsersStatus'] = 1;
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

        $previousPageUrl = null;
        $nextPageUrl = null;

        if($page > 1){
            $previousPageUrl = URL::mergeOn($_GET, ['page' => $page - 1]);
        }

        if($page < $pages && $pages !== 1){
            $nextPageUrl = URL::mergeOn($_GET, ['page' => $page + 1]);
        }

        return $this->view->render('adminUsers', [
            'users' => $users,
            'page' => $page,
            'pages' => $pages,
            'totalUsersCount' => $usersCount,
            'pageUsersCount' => $pageUsersCount,
            'previousPageUrl' => $previousPageUrl,
            'nextPageUrl' => $nextPageUrl
        ]);
    }
}