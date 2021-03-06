<?php

namespace App\Src\Controller\BackController;

use App\Src\Controller\Controller;

abstract class BackController extends Controller
{
    protected $parameters;
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->checkAdmin();
        $this->CSRF();
    }
    
    protected function checkAdmin() : void
    {
        $this->checkLoggedIn();

        if (!($this->session->get('role') === 'admin')) {
            $this->session->addMessage('danger', 'Vous n\'avez pas le droit d\'accéder à cette page');
            $this->http->redirect('?');
        }
    }

    protected function buildLimitsList() : array
    {
        $i = 0;
        foreach ($this->limitArray as $limit) {  
            $list[$i]['value'] = (string)$limit;
            $list[$i]['name'] = (string)$limit;
            $i ++;
        }
        return $list ? $list : [];
    }

    protected function buildArticlesList() : array
    {
        $articlesList = $this->articleDAO->getArticles();
        $i = 0;
        foreach ($articlesList as $article) {  
            $list[$i]['value'] = $article->getId();
            $list[$i]['name'] = $article->getTitle();
            $i ++;
        }
        return $list ? $list : [];
    }

    protected function buildUsersList() : array
    {
        $users = $this->userDAO->getUsers();
        $i = 0;
        foreach ($users as $user) {  
            $list[$i]['value'] = $user->getId();
            $list[$i]['name'] = $user->getPseudo();
            $i ++;
        }
        return $list ? $list : [];
    }

    protected function buildCategoriesList() : array
    {
        $categories = $this->categoryDAO->getCategories();
        $i = 0;
        foreach ($categories as $category) {
            $list[$i]['value'] = $category->getId();
            $list[$i]['name'] = ucfirst($category->getName());
            $i ++;
        }
        return $list ? $list : [];
    }
}