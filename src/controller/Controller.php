<?php

namespace App\Src\Controller;

use App\Src\Constraint\Validation;
use App\Config\{Request, View, HTTP};
use App\Src\DAO\{ArticleDAO, CategoryDAO, CommentDAO, ReactionDAO, UserDAO};
use App\Src\Service\Mailer;
use App\Src\Utils\CSRF;


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
    protected $csrf;

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
        $this->csrf = new CSRF();
        $this->view = new View($this->session);
    }

    protected function checkLoggedIn()
    {
        if (!$this->session->get('pseudo')) {
            
            $this->session->addMessage('danger', 'Vous devez être connecté pour accèder à cette page');

            $this->setPreviousURI();

            $this->http->redirect('?route=login');
        }
        $this->CSRF();
    }

    /**
     * Storing previous URL for dynamic redirect later
     * @return void
     */
    protected function setPreviousURI() : void
    {
        if ($this->server->get('REQUEST_URI')) {
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
        $toStringArray = ['q', 'afterDatetime', 'beforeDatetime'];
        $toStringArticle = ['published', 'private', 'standby', 'all'];
        $toStringComment = ['validated','all'];
        $toStringUser = ['online', 'offline', 'banned', 'allUserStatus', 'admin', 'moderator', 'editor', 'user', 'allUserRoles'];
        $toString = array_merge($toStringArray,${'toString' . ucfirst($from)});

        $toIntegerArray = ['page','limit'];
        $toIntegerArticle = ['userId', 'authorId', 'categoryId'];
        $toIntegerComment = ['userId', 'articleId'];
        $toIntegerUser = ['scoreHigherThan','scoreLowerThan'];
        $toInteger = array_merge($toIntegerArray, ${'toInteger' . ucfirst($from)});

        foreach ($parameters as $key => $value) {
            if (in_array($key,$toString) && $value) {
                $cleanParameters[$key] = htmlentities($value);
            } elseif (in_array($key,$toInteger) && $value) {
                $cleanParameters[$key] = (int)$value;
            }            
        }
        return isset($cleanParameters) ? $cleanParameters : [] ;
    }

    protected function CSRF() : void
    {
        if (!$this->session->get('csrfToken') || $this->session->get('crsfTokenTime') + $this->csrf::REFRESH_TIME < time()) {
            $this->session->set('csrfToken', $this->csrf->generateToken());
            $this->session->set('crsfTokenTime', time());
        }
    }

    protected function checkToken(string $token, array $option = [])
    {
        if ($this->session->get('csrfToken') !== $token) {
            $this->session->addMessage('danger', 'Le lien de sécurité est manquant ou expiré, veuillez réessayer');
            // Save mode to avoid redirection and enable form posts backup 
            if (isset($option['saveMode']) && $option['saveMode'] === 1) {
                return 'expired';
            }
            $this->http->dynamicRedirect('?', $this->session);
        }
    }
}