<?php

namespace App\Config;

use App\Src\Controller\FrontController\{
    FrontController,
    FrontArticleController,
    FrontCommentController,
    FrontUserController
};
use App\Src\Controller\BackController\{
    BackAdminController,
    BackArticleController,
    BackCommentController,
    BackUserController
};
use App\Src\Controller\ErrorController\ErrorController;
use Exception;

class Router
{
    private $request;

    public function __construct()
    {
        $this->request = new Request();
    }
    
    public function run()
    {
        $route = $this->request->getGet()->get('route');
        try{
            if(isset($route)){
                switch ($route) {
                    case 'home' :
                        (new FrontController())->home($this->request->getPost());
                        break;
                    case 'articles' :
                        (new FrontArticleController())->articles($this->request->getGet());
                        break;
                    case 'article' :
                        (new FrontArticleController())->article($this->request->getGet());
                        break;
                    case 'addComment' :
                        (new FrontCommentController())->addComment($this->request->getPost(),(int)$this->request->getGet()->get('articleId'));
                        break;
                    case 'editComment' :
                        (new FrontCommentController())->editComment($this->request->getPost(),(int)$this->request->getGet()->get('commentId'));
                        break;
                    case 'frontDeleteComment' :
                        (new FrontCommentController())->deleteComment($this->request->getGet());
                        break;
                    case 'register' :
                        (new FrontUserController())->register($this->request->getPost());
                        break;
                    case 'login' :
                        (new FrontUserController())->login($this->request->getPost());
                        break;
                    case 'logout' :
                        (new FrontUserController())->logout();
                        break;
                    case 'profile' :
                        (new FrontUserController())->profile($this->request->getGet());
                        break;
                    case 'addArticle' :
                        (new BackArticleController())->addArticle($this->request->getPost());
                        break;                    
                    case 'editArticle' :
                        (new BackArticleController())->editArticle($this->request->getPost(),(int)$this->request->getGet()->get('articleId'));
                        break;
                    case 'updateArticleStatus' :
                        (new BackArticleController())->updateArticleStatus($this->request->getGet());
                        break;
                    case 'deleteArticle' :
                        (new BackArticleController())->deleteArticle($this->request->getGet());
                        break;
                    case 'deleteComment' :
                        (new BackCommentController())->deleteComment($this->request->getGet());
                        break;
                    case 'updateCommentValidation' :
                        (new BackCommentController())->updateCommentValidation($this->request->getGet());
                        break;
                    case 'viewSingleComment' :
                        (new BackCommentController())->viewSingleComment((int)$this->request->getGet()->get('commentId'));
                        break;
                    case 'adminEditComment' :
                        (new BackCommentController())->adminEditComment($this->request->getPost(),(int)$this->request->getGet()->get('commentId'));
                        break;
                    case 'administration' :
                        (new BackAdminController())->administration();
                        break;
                    case 'adminComments' :
                        (new BackAdminController())->adminComments($this->request->getGet());
                        break;
                    case 'adminArticles' :
                        (new BackAdminController())->adminArticles($this->request->getGet());
                        break;
                    case 'adminUsers' :
                        (new BackAdminController())->adminUsers($this->request->getGet());
                        break;
                    case 'updateUserRole' :
                        (new BackUserController())->updateUserRole($this->request->getGet());
                        break;
                    case 'updateUserStatus' :
                        (new BackUserController())->updateUserStatus($this->request->getGet());
                        break;
                    case 'deleteUser' :
                        (new BackUserController())->deleteUser($this->request->getGet());
                        break;    
                    default :
                        (new ErrorController())->errorNotFound();
                }     
            } else {
                (new FrontController())->home($this->request->getPost());
            }
        } catch (Exception $error) {
            (new ErrorController())->errorServer($error);
        }
    }
}
