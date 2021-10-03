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
    protected $http;
    private $request;
    protected $mailer;
    protected $get;
    protected $post;
    protected $server;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->articleDAO = new ArticleDAO();
        $this->categoryDAO = new CategoryDAO();
        $this->commentDAO = new CommentDAO();
        $this->reactionDAO = new ReactionDAO();
        $this->userDAO = new UserDAO();
        $this->http = new HTTP();
        $this->validation = new Validation();
        $this->request = new Request();
        $this->mailer = new Mailer();
        $this->get = $this->request->getGet();
        $this->post = $this->request->getPost();
        $this->server = $this->request->getServer();
        $this->session = $this->request->getSession();
        $this->view = new View($this->session);
    }

    protected function checkLoggedIn()
    {
        if(!$this->session->get('pseudo')) {
            
            $this->session->addMessage('danger', 'Vous devez être connecté pour accèder à cette page');

            $this->setPreviousURI();

            $this->http->redirect('?route=login');
        }
    }

    /**
     * Storing previous URL for dynamic redirect later
     * @return void
     */
    protected function setPreviousURI() : void
    {
        if($this->server->get('REQUEST_URI')){
            $this->session->set('previousURI', $this->server->get('REQUEST_URI'));
        }
    }

    /**
     * Returns cleaned given parameters (filter depending on origin $from : article/comment/user)
     * @param array $parameters
     * @param string $from
     * @return array
     */
    protected function getCleanParameters(array $parameters = [],string $from) : array
    {
        $toStringArray = ['q','afterDatetime','beforeDatetime'];
        $toStringArticle = ['published','private','standby','all'];
        $toStringComment = ['validated','all'];
        $toStringUser = ['online','offline','banned','allUserStatus','admin','moderator','editor','user','allUserRoles'];
        $toString = array_merge($toStringArray,${'toString' . ucfirst($from)});

        $toIntegerArray = ['page','limit'];
        $toIntegerArticle = ['userId','authorId','categoryId'];
        $toIntegerComment = ['userId','articleId'];
        $toIntegerUser = ['scoreHigherThan','scoreLowerThan'];
        $toInteger = array_merge($toIntegerArray,${'toInteger' . ucfirst($from)});

        foreach($parameters as $key => $value){
            if(in_array($key,$toString) && $value){
                $cleanParameters[$key] = htmlentities($value);
            } elseif(in_array($key,$toInteger) && $value){
                $cleanParameters[$key] = (int)$value;
            }            
        }
        return isset($cleanParameters) ? $cleanParameters : [] ;
    }
}