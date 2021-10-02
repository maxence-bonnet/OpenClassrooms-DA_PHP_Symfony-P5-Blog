<?php

namespace App\src\controller\backcontroller;

use App\config\Parameter;
use App\src\controller\Controller;
use App\src\utils\Text;

class BackArticleController extends BackController
{
    // ok QBuilder
    public function addArticle(Parameter $post)
    {
        $this->checkAdmin();

        if($post->get('submit')){

            $post->set('title', htmlspecialchars($post->get('title')));
            $post->set('lede', Text::HtmlToMarkdown($post->get('lede')));
            $post->set('content',Text::HtmlToMarkdown($post->get('content')));

            $errors = $this->validation->validate($post, 'Article');
            if(!$errors){
                // can be removed after better validation
                if(!$post->get('categoryId')){
                    $post->set('categoryId', 1);
                }
                //
                $post->set('createdAt', null);
                if((int)$post->get('statusId') !== 3){
                    $post->set('createdAt', date('Y-m-d H:i:s'));
                }   

                $this->articleDAO->addArticle($post);
                $this->session->addMessage('success', 'Le nouvel article a bien été ajouté');
                $this->http->redirect('?route=articles');
            }
            $this->data['post'] = $post;
            $this->data['errors'] = $errors;
        }

        $this->data['users'] = $this->buildUsersList();
        
        $this->data['categories'] = $this->buildCategoriesList();

        $this->data['title'] = 'Nouvel article';

        return $this->view->renderTwig('editArticle', $this->data);
    }

    // ok QBuilder
    public function editArticle(Parameter $post, int $articleId)
    {
        $this->checkAdmin();

        $this->data['users'] = $this->buildUsersList();
        
        $this->data['categories'] = $this->buildCategoriesList();

        $article = $this->articleDAO->getArticle($articleId);
        if(!$article){
            $this->session->addMessage('danger', 'L\'article recherché n\'existe pas / plus');  
            $this->http->dynamicRedirect('?route=adminArticles',$this->session);              
        }

        $post->set('id', $articleId);

        if($post->get('submit')){

            $post->set('title', htmlspecialchars($post->get('title')));
            $post->set('lede', Text::HtmlToMarkdown($post->get('lede')));
            $post->set('content',Text::HtmlToMarkdown($post->get('content')));

            $errors = $this->validation->validate($post, 'Article');
            if(!$errors){
                if($article->getCreatedat() === null && $post->get('statusId') !== 3){
                    $post->set('createdAt', date('Y-m-d H:i:s')); 
                }
                $post->set('lastModified', date('Y-m-d H:i:s'));    

                if(!$post->get('categoryId')){
                    $post->set('categoryId',"1");
                }
                $this->articleDAO->editArticle($post, $articleId);
                $this->session->addMessage('success', 'L\'article a bien été modifié');   
                $this->http->redirect('?route=article&articleId=' . $articleId);
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

    // ok QBuilder
    public function updateArticleStatus(int $articleId,int $statusId) : void
    {
        $this->checkAdmin();

        $this->parameters['articleId'] = $articleId;
        $this->parameters['statusId'] = $statusId;

        if($statusId === 1 || $statusId === 2){
            $article = $this->articleDAO->getArticle($articleId);
            if((int)$article->getStatusId() === 3 && $article->getCreatedAt() === null){
                $this->parameters['createdAt'] = date('Y-m-d H:i:s');
            }
        }
        $this->articleDAO->updateArticleStatus($this->parameters);
        $this->session->addMessage('success', 'Le statut de l\'article a bien été mis à jour');
        $this->http->dynamicRedirect('?route=adminArticles',$this->session);
    } 
    
    // ok QBuilder
    public function deleteArticle(int $articleId) : void
    {
        $this->checkAdmin();

        $this->articleDAO->deleteArticle($articleId);
        $this->session->addMessage('success', 'L\' article a bien été supprimé');
        $this->http->dynamicRedirect('?route=adminArticles',$this->session);
    }
}