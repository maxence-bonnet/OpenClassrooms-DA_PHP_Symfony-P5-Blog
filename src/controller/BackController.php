<?php

namespace App\src\controller;

use App\config\Parameter;

class BackController extends Controller
{
    private function checkLoggedIn()
    {
        if(!$this->session->get('pseudo')) {
            $this->session->set('loginNeeded', '<div class="alert alert-success">Vous devez être connecté pour accèder à cette page</div>');
            header('Location: ../public/index.php?route=login');
        } else {
            return true;
        }
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

    // en travaux
    public function editComment(Parameter $post, $commentId)
    {
        if($this->checkAdmin()) {
            $comment = $this->commentDAO->getComment($commentId);
            $articleId = $comment->getArticleId();          
            $article = $this->articleDAO->getArticle($articleId);
            $comments = $this->commentDAO->getCommentsFromArticle($articleId);
            if ($post->get('submit')) {
                $errors = $this->validation->validate($post, 'Comment');
                if (!$errors) {
                    $this->commentDAO->editComment($post, $commentId); // à l'avenir : $this->session->get('id') plutôt que $userId
                    $this->session->set('editedComment', '<div class="alert alert-success">Le commentaire a bien été modifié</div>');                
                    header('Location: ../public/index.php?route=article&articleId=' . $articleId);
                    exit();
                }
                return $this->view->render('article', [
                    'article' => $article,
                    'comments' => $comments,
                    'post' => $post,
                    'errors' => $errors
                ]);
            }
            $post->set('id', $comment->getId());
            $post->set('content', $comment->getContent());
            return $this->view->render('article', [
                'article' => $article,
                'comments' => $comments,
                'post' => $post
            ]);
        }
    }
    // ok
    public function deleteComment($commentId)
    {
        if($this->checkAdmin()) {
            $comment = $this->commentDAO->getComment($commentId);
            $articleId = $comment->getArticleId();
            $this->commentDAO->deleteComment($commentId);       
            $this->session->set('deletedComment', '<div class="alert alert-success">Le commentaire a bien été supprimé</div>');
            header('Location: ../public/index.php?route=article&articleId=' . $articleId);
        }
    }

    // ok
    public function login(Parameter $post)
    {
        if($post->get('submit')){
            if($this->userDAO->pseudoExists($post->get('pseudo'))){
                $result = $this->userDAO->login($post);
                if($result && $result['passwordValid']) {
                    $this->session->set('id', $result['result']['id']);
                    $this->session->set('role', $result['result']['name']);
                    $this->session->set('pseudo', $post->get('pseudo'));

                    $this->session->set('loginSuccess', '<div class="alert alert-success"> Vous êtes connecté en tant que ' . $post->get('pseudo') . '</div>');

                    header('Location: ../public/index.php');
                    exit();
                }
            }
            $this->session->set('LoginError', '<div class="alert alert-danger">Le pseudo ou mot de passe incorrect</div>');
            return $this->view->render('login', [
                'post'=> $post
            ]);    
        }
        return $this->view->render('login');
    }

    //
    public function logout()
    {
        $this->checkLoggedIn();

        $this->userDAO->logout($this->session->get('id'));

        $this->session->stop();
        $this->session->start();

        $this->session->set('logout', '<div class="alert alert-success">Vous êtes déconnecté, à bientôt !</div>');

        header('Location: ../public/index.php');
        exit();
    }

    
}