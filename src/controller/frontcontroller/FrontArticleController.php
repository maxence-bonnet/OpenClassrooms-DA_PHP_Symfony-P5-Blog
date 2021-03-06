<?php

namespace App\Src\Controller\FrontController;

use App\Config\Parameter;
use App\Src\Utils\URL;

class FrontArticleController extends FrontController
{
    private $defaultLimit = 9;
    private $parameters;
    private $data;

    public function articles(Parameter $get)
    {
        $this->parameters = $this->getCleanParameters($get->all(),'article');

        $this->data['page'] = isset($this->parameters['page']) && $this->parameters['page'] > 1 ? $this->parameters['page'] : 1;
        unset($this->parameters['page']);
        $this->data['title'] = 'Les articles';
        $this->data['get'] = $get;

        $this->parameters['limit'] = $this->defaultLimit ;

        // For all users -> published articles
        $this->parameters['published'] = 1;
        // For connected users -> add private articles
        if ($this->session->get('role')) {
            $this->parameters['private'] = 1;
        }

        $this->data['totalArticlesCount'] = (int) $this->articleDAO->countArticles($this->parameters);

        $this->data['pages'] = ceil($this->data['totalArticlesCount']/$this->parameters['limit']);

        if ($this->data['page'] <= $this->data['pages'] && $this->data['page'] > 1) {
            $this->parameters['offset'] = $this->parameters['limit']*($this->data['page'] - 1);     
        } else {
            $this->data['page'] = 1;
            $this->parameters['offset'] = 0;
        }

        $this->data['articles'] = $this->articleDAO->getArticles($this->parameters);

        $this->data['withCountCategories'] = $this->articleDAO->countByCategory($this->parameters);

        $this->data['pageArticlesCount'] = count($this->data['articles']);

        $this->data['previousPageURL'] = $this->data['page'] > 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] - 1]) . "#resultsTable" : null;
        $this->data['nextPageURL'] = $this->data['page'] < $this->data['pages'] && $this->data['pages'] !== 1 ? URL::mergeOn($this->get->all(),['page' => $this->data['page'] + 1]) . "#resultsTable" : null;

        return $this->view->renderTwig('articles', $this->data);
    }

    public function article(Parameter $get)
    {
        $article = $this->articleDAO->getArticle((int)$get->get('articleId'));

        if ($article) {
            $this->setPreviousURI();

            if ($this->session->get('pseudo')) {
                if ($article->getStatusName() === "standby" && ($this->session->get('role') !== "admin" && $this->session->get('role') !== "editor")) {
                    $this->session->addMessage('danger', 'L\'article recherch?? est n\'est pas encore publi?? ou n\'est plus disponnible');
                    $this->http->redirect('?route=articles');                  
                }
            } else {
                if ($article->getStatusName() === "private") {
                    $this->session->addMessage('danger', 'L\'article recherch?? est priv??, vous devez vous connecter pour pouvoir le consulter');
                    $this->http->redirect('?route=articles');
                } elseif ($article->getStatusName() === "standby") {
                    $this->session->addMessage('danger', 'L\'article recherch?? est n\'est pas encore publi?? ou n\'est plus disponnible');
                    $this->http->redirect('?route=articles');                  
                }             
            }
            $comments = [];
            if ($article->getAllowComment()) {
                $comments = $this->commentDAO->getComments([
                    'articleId' => (int)$get->get('articleId'),
                    'validated' => 'validated',
                    'orderBy' => [
                        'column' => 'c.created_at',
                        'order' => 'ASC'
                    ]
                ]);
            }
            $this->data['title'] = $article->getTitle();
            $this->data['article'] = $article;
            $this->data['comments'] = $comments;
            $this->data['answerTo'] = $get->get('answerTo');
            return $this->view->renderTwig('article', $this->data);
        }
        $this->session->addMessage('danger', 'L\'article recherch?? n\'existe pas');
        $this->http->redirect('?route=articles');
    }
}