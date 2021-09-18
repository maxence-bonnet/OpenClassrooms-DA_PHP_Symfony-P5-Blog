<?php

namespace App\src\controller;

use App\src\constraint\Validation;
use App\config\Request;
use App\config\View;
use App\config\HTTP;
use App\src\DAO\ArticleDAO;
use App\src\DAO\CategoryDAO;
use App\src\DAO\CommentDAO;
use App\src\DAO\ReactionDAO;
use App\src\DAO\UserDAO;
use App\src\model\Mailer;


abstract class Controller
{
    protected $articleDAO;
    protected $commentDAO;
    protected $userDAO;
    protected $view;
    private $request;
    protected $mailer;
    protected $get;
    protected $post;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->articleDAO = new ArticleDAO();
        $this->categoryDAO = new CategoryDAO();
        $this->commentDAO = new CommentDAO();
        $this->reactionDAO = new ReactionDAO();
        $this->userDAO = new UserDAO();
        $this->view = new View();
        $this->validation = new Validation();
        $this->request = new Request();
        $this->mailer = new Mailer();
        $this->get = $this->request->getGet();
        $this->post = $this->request->getPost();
        $this->session = $this->request->getSession();
    }

    protected function checkLoggedIn()
    {
        if(!$this->session->get('pseudo')) {
            $this->session->set('loginNeeded', '<div class="alert alert-danger">Vous devez être connecté pour accèder à cette page</div>');
            HTTP::redirect('?route=login');
        }
    }
}