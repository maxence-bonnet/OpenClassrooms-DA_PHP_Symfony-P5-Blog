<?php

namespace App\src\controller\frontcontroller;

use App\config\Parameter;
use App\src\utils\URL;

class FrontArticleController extends FrontController
{
    public function articles(Parameter $get)
    {
        $page = 1;
        $offset = 0;
        $limit = 9;
        $parameters['page'] = &$page;
        $parameters['limit'] = &$limit;
        $parameters['offset'] = &$offset;
        $parameters['published'] = 1;
        $parameters['private'] = null;
        $parameters['oderby'] = 'DESC';
        $data = &$parameters;

        if(!empty($get->get('q'))){
            $parameters['q'] = htmlentities($get->get('q'));
        }

        if($this->session->get('role')){
            $parameters['private'] = 1;
        }

        if((int)$get->get('page') > 1){
            $page = $get->get('page');
        }

        $data['totalArticlesCount'] = (int) $this->articleDAO->countArticles($parameters);

        $pages = ceil($data['totalArticlesCount']/$limit);

        if($page <= $pages && $page > 1){
            $offset = $limit*($page - 1);         
        } else {
            $page = 1;      
        }

        $data['articles'] = $this->articleDAO->getArticles($parameters);

        $data['pageArticlesCount'] = count($data['articles']);

        $data['previousPageURL'] = null;
        $data['nextPageURL'] = null;
        
        if($page > 1){
            $data['previousPageURL'] = URL::mergeOn($this->get->all(), ['page' => $page - 1]);
        }

        if($page < $pages && $pages !== 1){
            $data['nextPageURL'] = URL::mergeOn($this->get->all(), ['page' => $page + 1]);
        }

        $data['pages'] = $pages;
        $data['title'] = 'Les Articles';
        $data['get'] = $get;

        return $this->view->renderTwig('articles', $data);
    }

    public function article(Parameter $get)
    {
        $article = $this->articleDAO->getArticle((int)$get->get('articleId'));
        if($article){
            $this->setPreviousURI();

            if($this->session->get('pseudo')){
                if($article->getStatusName() === "standby" && ($this->session->get('role') !== "admin" && $this->session->get('role') !== "editor")){
                    $this->session->addMessage('danger', 'L\'article recherché est n\'est pas encore publié ou n\'est plus disponnible');
                    $this->http->redirect('?route=articles');                  
                }
            } else {
                if($article->getStatusName() === "private"){
                    $this->session->addMessage('danger', 'L\'article recherché est privé, vous devez vous connecter pour pouvoir le consulter');
                    $this->http->redirect('?route=articles');
                } elseif($article->getStatusName() === "standby"){
                    $this->session->addMessage('danger', 'L\'article recherché est n\'est pas encore publié ou n\'est plus disponnible');
                    $this->http->redirect('?route=articles');                  
                }             
            }
            $comments = [];
            if($article->getAllowComment()) {
                $comments = $this->commentDAO->getComments([
                    'articleId' => (int)$get->get('articleId'),
                    'validated' => "validated"
                ]);
            }
            $data['title'] = $article->getTitle();
            $data['article'] = $article;
            $data['comments'] = $comments;
            $data['answerTo'] = $get->get('answerTo');
            return $this->view->renderTwig('article', $data);
        }
        $this->session->addMessage('danger', 'L\'article recherché n\'existe pas');
        $this->http->redirect('?route=articles');
    }
}