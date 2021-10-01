<?php

namespace App\src\controller\backcontroller;

use App\config\Parameter;
use App\src\controller\Controller;
use App\src\utils\Text;

class BackArticleController extends BackController
{
    public function addArticle(Parameter $post)
    {
        $this->checkAdmin();

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Article');
            if(!$errors){
                $post->set('lede', Text::HtmlToMarkdown($post->get('lede')));
                $post->set('content',Text::HtmlToMarkdown($post->get('content')));
                $this->articleDAO->addArticle($post);
                $this->session->addMessage('success', 'Le nouvel article a bien été ajouté');
                $this->http->redirect('?route=articles');
            }
            $data['post'] = $post;
            $data['errors'] = $errors;
        }

        $users = $this->userDAO->getUsers();
        $i = 0;
        foreach($users as $user){  
            $data['users'][$i]['value'] = $user->getId();
            $data['users'][$i]['name'] = $user->getPseudo();
            $i ++;
        }
        
        $categories = $this->categoryDAO->getCategories();
        $i = 0;
        foreach($categories as $category){
            $data['categories'][$i]['value'] = $category->getId();
            $data['categories'][$i]['name'] = $category->getName();
            $i ++;
        }

        $data['title'] = 'Nouvel article';

        return $this->view->renderTwig('editArticle', $data);
    }

    public function editArticle(Parameter $post, $articleId)
    {
        $this->checkAdmin();

        // Users list for <select>
        $users = $this->userDAO->getUsers();
        $i = 0;
        foreach($users as $user){  
            $data['users'][$i]['value'] = $user->getId();
            $data['users'][$i]['name'] = $user->getPseudo();
            $i ++;
        }
        
        // Categories list for <select>
        $categories = $this->categoryDAO->getCategories();
        $i = 0;
        foreach($categories as $category){
            $data['categories'][$i]['value'] = $category->getId();
            $data['categories'][$i]['name'] = $category->getName();
            $i ++;
        }

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Article');
            if(!$errors){
                $post->set('lede', Text::HtmlToMarkdown($post->get('lede')));
                $post->set('content',Text::HtmlToMarkdown($post->get('content')));
                if(!$post->get('categoryId')){
                    $post->set('categoryId',"1");
                }
                $this->articleDAO->editArticle($post, $articleId);
                $this->session->addMessage('success', 'L\'article a bien été modifié');   
                $this->http->redirect('?route=article&articleId=' . $articleId);
            }
            $data['title'] = 'Modification : ' . $post->get('Title');
            $data['post'] = $post;
            $data['errors'] = $errors;
            return $this->view->renderTwig('editArticle', $data);
        }

        $article = $this->articleDAO->getArticle($articleId);

        $post->set('id', $article->getId());
        $post->set('title', $article->getTitle());
        $post->set('categoryId', $article->getCategoryId());
        $post->set('categoryName', $article->getCategoryName());
        $post->set('lede', $article->getLede());
        $post->set('content', $article->getContent());
        $post->set('authorPseudo', $article->getAuthorPseudo());
        $post->set('authorId', $article->getAuthorId());
        $post->set('allowComment', $article->getAllowComment());
        $post->set('statusId', $article->getStatusId());

        $data['title'] = 'Modification : ' . $article->getTitle();
        $data['post'] = $post;

        return $this->view->renderTwig('editArticle', $data);
    }

    public function updateArticleStatus(int $articleId,int $statusId)
    {
        $this->checkAdmin();

        $date = null;
        if($statusId === 1 || $statusId === 2){
            $article = $this->articleDAO->getArticle($articleId);
            if((int)$article->getStatusId() === 3 && $article->getCreatedAt() === null){
                $date = date('Y-m-d H:i:s');
            }
        }
        $this->articleDAO->updateArticleStatus($articleId, $statusId, $date);
        $this->session->addMessage('success', 'Le statut de l\'article a bien été mis à jour');
        $this->http->dynamicRedirect('?route=adminArticles',$this->session);
    } 
    
    public function deleteArticle($articleId)
    {
        $this->checkAdmin();

        $this->articleDAO->deleteArticle($articleId);
        $this->session->addMessage('success', 'L\' article a bien été supprimé');
        $this->http->dynamicRedirect('?route=adminArticles',$this->session);
    }
}