<?php

namespace App\src\controller;

use App\config\Parameter;

class BackController extends Controller
{
    private function checkLoggedIn()
    {
        // if(!$this->session->get('pseudo')) {
        //     $this->session->set('need_login', 'Vous devez vous connecter pour accéder à cette page');
        //     header('Location: ../public/index.php?route=login');
        // } else {
        //     return true;
        // }
        return true;
    }

    private function checkAdmin()
    {
        // $this->checkLoggedIn();
        // if(!($this->session->get('role') === 'admin')) {
        //     $this->session->set('not_admin', 'Vous n\'avez pas le droit d\'accéder à cette page');
        //     header('Location: ../public/index.php?route=profile');
        // } else {
        //     return true;
        // }
        return true;
    }

    // ok
    public function addArticle(Parameter $post)
    {
        if($this->checkAdmin()) {
            if ($post->get('submit')) {
                $errors = $this->validation->validate($post, 'Article');
                if (!$errors) {
                    $this->articleDAO->addArticle($post, $post->get('authorId')); // à l'avenir : $this->session->get('id') plutôt que $post->get('authorId')
                    $this->session->set('addedArticle', '<div class="alert alert-success">Le nouvel article a bien été ajouté</div>');
                    header('Location: ../public/index.php?route=articles');
                }
                return $this->view->render('edit_article', [
                    'post' => $post,
                    'errors' => $errors
                ]);
            }
            return $this->view->render('edit_article');
        }
    }

    // ok
    public function editArticle(Parameter $post, $articleId)
    {
        if($this->checkAdmin()) {           
            if ($post->get('submit')) {
                $errors = $this->validation->validate($post, 'Article');
                if (!$errors) {
                    $this->articleDAO->editArticle($post, $articleId, $post->get('authorId')); // à l'avenir : $this->session->get('id') plutôt que $post->get('authorId')
                    $this->session->set('editedArticle', '<div class="alert alert-success">L\'article a bien été modifié</div>');                
                    header('Location: ../public/index.php?route=article&articleId=' . $articleId);
                }
                return $this->view->render('edit_article', [
                    'post' => $post,
                    'errors' => $errors
                ]);

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
            $post->set('statusId', $article->getstatusId());
            return $this->view->render('edit_article', [
                'post' => $post
            ]);
        }
    }

    // ok
    public function deleteArticle($articleId)
    {
        if($this->checkAdmin()) {
            $this->articleDAO->deleteArticle($articleId);
            $this->session->set('deletedArticle', '<div class="alert alert-success">L\' article a bien été supprimé</div>');
            header('Location: ../public/index.php?route=articles');
        }
    }
    
    // ok
    public function deleteComment($commentId)
    {
        if($this->checkAdmin()) {
            $this->commentDAO->deleteComment($commentId);
            $this->session->set('deletedComment', '<div class="alert alert-success">Le commentaire a bien été supprimé</div>');
            header('Location: ../public/index.php?route=articles');
        }
    }
}