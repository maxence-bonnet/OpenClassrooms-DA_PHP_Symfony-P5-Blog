<?php

namespace App\src\controller;

use App\config\Parameter;
use App\src\constraint\Constraint;
use App\config\HTTP;

class FrontController extends Controller
{
    // ok
    public function home(Parameter $post)
    {
        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'ContactForm');
            if(!$errors){
                $result = $this->mailer->sendContactForm($post);
                if($result){
                    $this->session->set('messageSent', '<div class="alert alert-success">Votre message a bien été envoyé</div>');
                    HTTP::redirect('?');
                }
                $this->session->set('messageFailure', '<div class="alert alert-danger">Échec lors de l\'envoi du message</div>');       
            }
            return $this->view->render('home', [
                'post' =>$post,
                'errors' => $errors
            ]);            
        }
        return $this->view->render('home');
    }

    // ok
    public function register(Parameter $post)
    {
        if($this->session->get('pseudo')){
            $this->session->set('alreadyLoggedIn', '<div class="alert alert-danger">Vous ne pouvez pas vous inscrire en étant déjà connecté</div>');
            HTTP::redirect('../public/');
        }

        if($post->get('submit')){
            // Special error needing DAO
            if($this->userDAO->pseudoExists($post->get('pseudo'))){
                $post->set('pseudoExists', Constraint::EXISTING_PSEUDO);       
            }
            $errors = $this->validation->validate($post, 'User');
            if(!$errors){
                $this->userDAO->register($post);
                $this->session->set('Registered', '<div class="alert alert-success">Inscription réussie</div>');
                HTTP::redirect('?route=login');
            }
            return $this->view->render('register', [
                'post' =>$post,
                'errors' => $errors
            ]);
        }
        return $this->view->render('register');
    }

    // ok
    public function articles(Parameter $get)
    {
        $page = 1;
        $limit = 6;
        $published = 1;
        $private = null;
        // $standby = null;

        if($this->session->get('role')){
            $private = 1;
            // if($this->session->get('role') === "admin"){
            //     $standby = 1;
            // }
        }

        if((int)$get->get('page') > 1){
            $page = $get->get('page');
        }

        $articlesCount = (int) $this->articleDAO->countArticles([
            'published' => $published,
            'private' => $private,
            // 'standby' => $standby
        ]);

        $pages = ceil($articlesCount/$limit);

        if($page <= $pages && $page > 1){
            $offset = $limit*($page - 1);         
        } else {
            $page = 1;
            $offset = 0;
        }

        $articles = $this->articleDAO->getArticles([
            'published' => $published,
            'private' => $private,
            // 'standby' => $standby,
            'orderby' => 'DESC',
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return $this->view->render('articles', [
            'articles' => $articles,
            'page' => $page,
            'pages' => $pages
        ]);
    }

    // ok
    public function article(Parameter $get)
    {
        $article = $this->articleDAO->getArticle((int)$get->get('articleId'));
        if($article){
            $comments = [];
            if($article->getAllowComment()) {
                $comments = $this->commentDAO->getComments([
                    'articleId' => (int)$get->get('articleId'),
                    'validated' => "validated"
                ]);
            }                        
            return $this->view->render('article', [
                'article' => $article,
                'comments' => $comments,
                'answerTo' => $get->get('answerTo')
            ]);
        }
        $this->session->set('unfoundArticle', '<div class="alert alert-danger">L\'article recherché n\'existe pas</div>');
        HTTP::redirect('?route=articles');
    }

    // ok
    public function addComment(Parameter $post, $articleId)
    {
        $this->checkLoggedIn();

        $article = $this->articleDAO->getArticle($articleId);
        if($article->getAllowComment()){
            if($post->get('submit')) {
                $errors = $this->validation->validate($post, 'Comment');
                if(!$errors) {
                    $parameters['userId'] = $this->session->get('id');
                    $parameters['articleId'] = $articleId;
                    $parameters['content'] = htmlspecialchars($post->get('content'));

                    if($post->get('answerTo')){
                        $parameters['answerTo'] = (int)$post->get('answerTo');
                    }

                    if($this->session->get('role') === "admin" || $this->session->get('role') === "moderator"){
                        $parameters['validated'] = 1;
                        $this->session->set('addedComment', '<div class="alert alert-success">Le commentaire a bien été publié</div>');
                    } else {
                        $parameters['validated'] = 0;
                        $this->session->set('addedComment', '<div class="alert alert-success">Le commentaire a bien été envoyé (soumis à validation avant publication)</div>');
                    }

                    $this->commentDAO->addComment($parameters);
                    HTTP::redirect('?route=article&articleId=' . $articleId);
                }
                $comments = $this->commentDAO->getComments([
                    'articleId' => $articleId,
                    'validated' => "validated"
                ]);
                return $this->view->render('article', [
                    'article' => $article,
                    'comments' => $comments,
                    'post' => $post,
                    'errors' => $errors
                ]);
            }
        }
        HTTP::redirect('?route=article&articleId=' . $articleId);
    }

    // TRAVAUX
    public function editComment(Parameter $post, $commentId)
    {
        $this->checkLoggedIn();

        $comment = $this->commentDAO->getComment($commentId);
        $articleId = $comment->getArticleId();
        if($this->session->get('role') !== "admin"){
            if($this->session->get('role') !== "moderator" && $this->session->get('id') !== $comment->getUserId()){
                $this->session->set('canNotEditComment', '<div class="alert alert-danger">Vous ne pouvez pas modifier les commentaires d\'autres personnes</div>');
                HTTP::redirect('?route=article&articleId=' . $articleId);
            }
        }

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Comment');
            if (!$errors){
                if($this->session->get('role') === "admin" || $this->session->get('role') === "moderator"){
                    $validated = 1;
                    $this->session->set('editedComment', '<div class="alert alert-success">Le commentaire a bien été modifié</div>');
                } else {
                    $validated = 0;
                    $this->session->set('editedComment', '<div class="alert alert-success">Le commentaire a bien été modifié (en attente d\'une nouvelle validation avant publication)</div>');
                }
                $this->commentDAO->editComment($post, $commentId, $validated);
                HTTP::redirect('?route=article&articleId=' . $articleId);
            }
            return $this->view->render('article', [
                'article' => $article,
                'comments' => $comments,
                'post' => $post,
                'errors' => $errors
            ]);
        }

        $article = $this->articleDAO->getArticle($articleId);
        $comments = $this->commentDAO->getComments([
            'articleId' => $articleId,
            'validated' => "validated"
        ]);

        $post->set('id', $comment->getId());
        $post->set('content', $comment->getContent());
        return $this->view->render('article', [
            'article' => $article,
            'comments' => $comments,
            'post' => $post
        ]);
    }

    // ok
    public function login(Parameter $post)
    {
        if($this->session->get('pseudo')){
            $this->session->set('alreadyLoggedIn', '<div class="alert alert-danger">Vous ne pouvez pas effectuer cette action en étant déjà connecté</div>');
            HTTP::redirect('?');
        }

        if($post->get('submit')){
            if($this->userDAO->pseudoExists($post->get('pseudo'))){
                $result = $this->userDAO->login($post);
                if($result && $result['passwordValid']) {
                    $this->session->set('id', $result['result']['id']);
                    $this->session->set('role', $result['result']['role_name']);
                    $this->session->set('pseudo', $post->get('pseudo'));
                    $this->session->set('loginSuccess', '<div class="alert alert-success"> Vous êtes connecté en tant que ' . $post->get('pseudo') . '</div>');
                    HTTP::redirect('?');
                }
            }
            $this->session->set('LoginError', '<div class="alert alert-danger">Le pseudo ou mot de passe incorrect</div>');
            return $this->view->render('login', [
                'post'=> $post
            ]);    
        }
        return $this->view->render('login');
    }

    // ok
    public function logout()
    {
        $this->checkLoggedIn();

        $this->userDAO->logout($this->session->get('id'));

        $this->session->stop();
        $this->session->start();

        $this->session->set('logout', '<div class="alert alert-success">Vous êtes déconnecté, à bientôt !</div>');

        HTTP::redirect('?');
    }
}

