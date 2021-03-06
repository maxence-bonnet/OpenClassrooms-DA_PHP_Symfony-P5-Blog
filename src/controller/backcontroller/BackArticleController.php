<?php

namespace App\Src\Controller\BackController;

use App\Config\Parameter;
use App\Src\Controller\Controller;
use App\Src\Utils\Text;

class BackArticleController extends BackController
{
    public function addArticle(Parameter $post)
    {
        if ($post->get('submit') && $post->get('token')) {

            $tokenState = $this->checkToken((string)$post->get('token'), ['saveMode' => 1]);

            $post->set('title', htmlspecialchars($post->get('title')));
            $post->set('lede', Text::HtmlToMarkdown($post->get('lede')));
            $post->set('content',Text::HtmlToMarkdown($post->get('content')));

            $errors = $this->validation->validate($post, 'Article');
            if (!$errors && $tokenState !== 'expired') {
                $post->set('createdAt', null);
                if ((int)$post->get('statusId') !== 3) {
                    $post->set('createdAt', date('Y-m-d H:i:s'));
                }   

                $this->articleDAO->addArticle($post);
                $this->session->addMessage('success', 'Le nouvel article a bien été ajouté');
                $this->http->redirect('?route=articles');
            }
            if (isset($errors['missingField'])) {
                $this->session->addMessage('danger','Un ou plusieurs champs manquant.');
            }
            $this->data['post'] = $post;
            $this->data['errors'] = $errors;
        }

        $this->data['users'] = $this->buildUsersList();
        
        $this->data['categories'] = $this->buildCategoriesList();

        $this->data['title'] = 'Nouvel article';

        return $this->view->renderTwig('editArticle', $this->data);
    }

    public function editArticle(Parameter $post, int $articleId)
    {
        $this->data['users'] = $this->buildUsersList();
        
        $this->data['categories'] = $this->buildCategoriesList();

        $article = $this->articleDAO->getArticle($articleId);
        if (!$article) {
            $this->session->addMessage('danger', 'L\'article recherché n\'existe pas / plus');  
            $this->http->dynamicRedirect('?route=adminArticles',$this->session);              
        }

        $post->set('id', $articleId);

        if ($post->get('submit') && $post->get('token')) {

            $tokenState = $this->checkToken((string)$post->get('token'), ['saveMode' => 1]);

            $post->set('title', htmlspecialchars($post->get('title')));
            $post->set('lede', Text::HtmlToMarkdown($post->get('lede')));
            $post->set('content',Text::HtmlToMarkdown($post->get('content')));

            $errors = $this->validation->validate($post, 'Article');
            if (!$errors && $tokenState !== 'expired') {
                if ($article->getCreatedat() === null && $post->get('statusId') !== 3) {
                    $post->set('createdAt', date('Y-m-d H:i:s')); 
                }
                $post->set('lastModified', date('Y-m-d H:i:s'));    

                if (!$post->get('categoryId')) {
                    $post->set('categoryId',"1");
                }
                $this->articleDAO->editArticle($post, $articleId);
                $this->session->addMessage('success', 'L\'article a bien été modifié');   
                $this->http->redirect('?route=article&articleId=' . $articleId);
            }
            if (isset($errors['missingField'])) {
                $this->session->addMessage('danger','Un ou plusieurs champs manquant.');
            }
            $this->data['title'] = 'Modification : ' . $post->get('title');
            $this->data['post'] = $post;
            $this->data['errors'] = $errors;

            return $this->view->renderTwig('editArticle', $this->data);
        }

        $post->set('title', $article->getTitle());
        $post->set('categoryId', $article->getCategoryId());
        $post->set('categoryName', $article->getCategoryName());
        $post->set('lede', $article->getLede());
        $post->set('content', $article->getContent());
        $post->set('authorPseudo', $article->getAuthorPseudo());
        $post->set('authorId', $article->getAuthorId());
        $post->set('allowComment', $article->getAllowComment());
        $post->set('statusId', $article->getStatusId());

        $this->data['title'] = 'Modification : ' . $article->getTitle();
        $this->data['article'] = $article;
        $this->data['post'] = $post;

        return $this->view->renderTwig('editArticle', $this->data);
    }

    public function updateArticleStatus(Parameter $get) : void
    {
        if ($get->get('articleId') && $get->get('statusId') && $get->get('token')) {

            $this->checkToken((string)$get->get('token'));

            $this->parameters['articleId'] = (int)$get->get('articleId');
            $this->parameters['statusId'] = (int)$get->get('statusId');

            if ($get->get('statusId') === 1 || $get->get('statusId') === 2) {

                $article = $this->articleDAO->getArticle((int)$get->get('articleId'));

                if ((int)$article->getStatusId() === 3 && $article->getCreatedAt() === null) {
                    $this->parameters['createdAt'] = date('Y-m-d H:i:s');
                }
            }
            $this->articleDAO->updateArticleStatus($this->parameters);
            $this->session->addMessage('success', 'Le statut de l\'article a bien été mis à jour');
        }

        $this->http->dynamicRedirect('?route=adminArticles',$this->session);
    } 

    public function deleteArticle(Parameter $get) : void
    {
        if ($get->get('articleId') && $get->get('token')) {

            $this->checkToken((string)$get->get('token'));

            $this->articleDAO->deleteArticle((int)$get->get('articleId'));

            $this->session->addMessage('success', 'L\' article a bien été supprimé');
        }
        $this->http->dynamicRedirect('?route=adminArticles',$this->session);
    }
}