<?php

namespace App\config;

use App\src\controller\{FrontController,
                        BackController,
                        ErrorController};
use Exception;

class Router
{
    private $frontController;
    private $errorController;
    private $backController;
    private $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->frontController = new FrontController();
        $this->backController = new BackController();
        $this->errorController = new ErrorController();
    }

    public function run()
    {
        $route = $this->request->getGet()->get('route');
        try{            
            if(isset($route)){
                if($route === 'home'){
                    $this->frontController->home($this->request->getPost());
                } elseif($route === 'articles'){
                    $this->frontController->articles($this->request->getGet());
                } elseif($route === 'article'){
                    $this->frontController->article((int) $this->request->getGet()->get('articleId'));
                } elseif($route === 'addArticle'){
                    $this->backController->addArticle($this->request->getPost());
                } elseif($route === 'editArticle'){
                    $this->backController->editArticle($this->request->getPost(),(int)$this->request->getGet()->get('articleId'));
                } elseif($route === 'updateArticleStatus'){
                    $this->backController->updateArticleStatus((int)$this->request->getGet()->get('articleId'),(int)$this->request->getGet()->get('statusId'));
                } elseif($route === 'deleteArticle'){
                    $this->backController->deleteArticle((int)$this->request->getGet()->get('articleId'));
                } elseif($route === 'addComment'){
                    $this->frontController->addComment($this->request->getPost(),(int)$this->request->getGet()->get('articleId'));
                } elseif($route === 'deleteComment'){
                    $this->backController->deleteComment((int)$this->request->getGet()->get('commentId'));
                } elseif($route === 'updateCommentValidation'){
                    $this->backController->updateCommentValidation((int)$this->request->getGet()->get('commentId'),(int)$this->request->getGet()->get('validation'));
                } elseif($route === 'editComment'){
                    $this->frontController->editComment($this->request->getPost(),(int)$this->request->getGet()->get('commentId'));
                } elseif($route === 'register'){
                    $this->frontController->register($this->request->getPost());
                } elseif($route === 'login'){
                    $this->frontController->login($this->request->getPost());
                } elseif($route === 'logout'){
                    $this->frontController->logout();
                } elseif($route === 'administration'){
                    $this->backController->administration();
                } elseif($route === 'viewSingleComment'){
                    $this->backController->viewSingleComment((int)$this->request->getGet()->get('commentId'));
                } elseif($route === 'adminEditComment'){
                    $this->backController->adminEditComment($this->request->getPost(),(int)$this->request->getGet()->get('commentId'));
                } elseif($route === 'adminComments'){
                    $this->backController->adminComments($this->request->getGet());
                } elseif($route === 'adminArticles'){
                    $this->backController->adminArticles($this->request->getGet());
                } elseif($route === 'adminUsers'){
                    $this->backController->adminUsers($this->request->getGet());
                } elseif($route === 'updateUserRole'){
                    $this->backController->updateUserRole((int)$this->request->getGet()->get('userId'),(int)$this->request->getGet()->get('roleId'));
                } else {
                    $this->errorController->errorNotFound();
                }
            } else {
                $this->frontController->home($this->request->getPost());
            }
        }
        catch (Exception $e)
        {
            $this->errorController->errorServer();
        }
    }
}