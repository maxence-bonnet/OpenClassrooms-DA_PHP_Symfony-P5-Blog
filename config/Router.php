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
        // isset($_SESSION) ? var_dump($_SESSION) : '';
        // try{            
            if(isset($route))
            {
                if($route === 'articles'){
                    $this->frontController->articles();
                } elseif($route === 'article'){
                    $this->frontController->article((int) $this->request->getGet()->get('articleId'));
                } elseif($route === 'addArticle'){
                    $this->backController->addArticle($this->request->getPost());
                } elseif($route === 'editArticle'){
                    $this->backController->editArticle($this->request->getPost(),$this->request->getGet()->get('articleId'));
                } elseif($route === 'deleteArticle'){
                    $this->backController->deleteArticle($this->request->getGet()->get('articleId'));
                } elseif($route === 'addComment'){
                    $this->frontController->addComment($this->request->getPost(),$this->request->getGet()->get('articleId'));
                } else {
                    $this->errorController->errorNotFound();
                }
                /**
                 * EN TRAVAUX
                 */
            }
            else{
                $this->frontController->home();
            }
        // }
        // catch (Exception $e)
        // {
        //     $this->errorController->errorServer();
        // }
    }
}