<?php

namespace App\config;

use App\src\controller\frontcontroller\{FrontController,
                                        FrontArticleController,
                                        FrontCommentController,
                                        FrontUserController};
use App\src\controller\backcontroller\{BackController,
                                        BackAdminController,
                                        BackArticleController,
                                        BackCommentController,
                                        BackUserController};
use App\src\controller\errorcontroller\ErrorController;
use Exception;

class Router
{
    private $frontController;
    private $frontArticleController;    
    private $frontCommentController;
    private $frontUserController;
    private $errorController;
    private $backController;
    private $backAdminController;
    private $backArticleController;
    private $backCommentController;
    private $backUserController;
    private $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->frontController = new FrontController();
        $this->frontArticleController = new FrontArticleController();
        $this->frontCommentController = new FrontCommentController();
        $this->frontUserController = new FrontUserController();
        $this->backController = new BackController();
        $this->backAdminController = new BackAdminController();
        $this->backArticleController = new BackArticleController();
        $this->backCommentController = new BackCommentController();
        $this->backUserController = new BackUserController();
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
                    $this->frontArticleController->articles($this->request->getGet());
                } elseif($route === 'article'){
                    $this->frontArticleController->article($this->request->getGet());
                } elseif($route === 'addComment'){
                    $this->frontCommentController->addComment($this->request->getPost(),(int)$this->request->getGet()->get('articleId'));
                } elseif($route === 'editComment'){
                    $this->frontCommentController->editComment($this->request->getPost(),(int)$this->request->getGet()->get('commentId'));
                } elseif($route === 'register'){
                    $this->frontUserController->register($this->request->getPost());
                } elseif($route === 'login'){
                    $this->frontUserController->login($this->request->getPost());
                } elseif($route === 'logout'){
                    $this->frontUserController->logout();
                } elseif($route === 'profile'){
                    $this->frontUserController->profile($this->request->getGet());
                } elseif($route === 'addArticle'){
                    $this->backArticleController->addArticle($this->request->getPost());
                } elseif($route === 'editArticle'){
                    $this->backArticleController->editArticle($this->request->getPost(),(int)$this->request->getGet()->get('articleId'));
                } elseif($route === 'updateArticleStatus'){
                    $this->backArticleController->updateArticleStatus((int)$this->request->getGet()->get('articleId'),(int)$this->request->getGet()->get('statusId'));
                } elseif($route === 'deleteArticle'){
                    $this->backArticleController->deleteArticle((int)$this->request->getGet()->get('articleId'));
                } elseif($route === 'deleteComment'){
                    $this->backCommentController->deleteComment((int)$this->request->getGet()->get('commentId'));
                } elseif($route === 'updateCommentValidation'){
                    $this->backCommentController->updateCommentValidation((int)$this->request->getGet()->get('commentId'),(int)$this->request->getGet()->get('validation'));
                } elseif($route === 'viewSingleComment'){
                    $this->backCommentController->viewSingleComment((int)$this->request->getGet()->get('commentId'));
                } elseif($route === 'adminEditComment'){
                    $this->backCommentController->adminEditComment($this->request->getPost(),(int)$this->request->getGet()->get('commentId'));
                } elseif($route === 'administration'){
                    $this->backAdminController->administration();
                } elseif($route === 'adminComments'){
                    $this->backAdminController->adminComments($this->request->getGet());
                } elseif($route === 'adminArticles'){
                    $this->backAdminController->adminArticles($this->request->getGet());
                } elseif($route === 'adminUsers'){
                    $this->backAdminController->adminUsers($this->request->getGet());
                } elseif($route === 'updateUserRole'){
                    $this->backUserController->updateUserRole((int)$this->request->getGet()->get('userId'),(int)$this->request->getGet()->get('roleId'));
                } elseif($route === 'updateUserStatus'){
                    $this->backUserController->updateUserStatus((int)$this->request->getGet()->get('userId'),(int)$this->request->getGet()->get('statusId'));
                } else {
                    $this->errorController->errorNotFound();
                }       
            } else {
                $this->frontController->home($this->request->getPost());
            }
        }
        catch (Exception $e)
        {
            $this->errorController->errorServer($e);
        }
    }
}