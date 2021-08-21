<?php

namespace App\src\controller;

use App\config\Parameter;

class FrontController extends Controller
{
    // ok
    public function home()
    {
        return $this->view->render('home');
    }

    // ok
    public function articles()
    {
        $articles = $this->articleDAO->getArticles();        
        return $this->view->render('articles', [
            'articles' => $articles
        ]);
    }

    // ok
    public function article(int $articleId)
    {
        $article = $this->articleDAO->getArticle($articleId);
        if($article){
            $comments = $this->commentDAO->getCommentsFromArticle($articleId);
            return $this->view->render('article', [
                'article' => $article,
                'comments' => $comments
            ]);
        }
        //$this->session->set('unfound_article', '<p>L\'article recherché n\'existe pas</p>');
        header('Location: ../public/index.php');
    }

    public function addComment(Parameter $post, $articleId)
    {
        if($post->get('submit')) {
            $errors = $this->validation->validate($post, 'Comment');
            if(!$errors) {
                $this->commentDAO->addComment($post, $articleId);
                $this->session->set('add_comment', '<p>Le nouveau commentaire a bien été ajouté</p>');
                header('Location: ../public/index.php');
            }
            $article = $this->articleDAO->getArticle($articleId);
            $comments = $this->commentDAO->getCommentsFromArticle($articleId);
            return $this->view->render('single', [
                'article' => $article,
                'comments' => $comments,
                'post' => $post,
                'errors' => $errors
            ]);
        }
    }

    public function register(Parameter $post)
    {
        if($post->get('submit')) {
            $errors = $this->validation->validate($post, 'User');
            if($this->userDAO->checkUser($post)) {
                $errors['pseudo'] = $this->userDAO->checkUser($post);
            }
            if(!$errors) {
                $this->userDAO->register($post);
                $this->session->set('register', '<p>Votre inscription a bien été effectuée</p>');
                header('Location: ../public/index.php');
            }
            return $this->view->render('register', [
                'post' => $post,
                'errors' => $errors
            ]);

        }
        return $this->view->render('register');
    }

    public function login(Parameter $post)
    {
        if($post->get('submit')) {
            $result = $this->userDAO->login($post);
            if($result && $result['isPasswordValid']) {
                $this->session->set('login', '<p>Bonjour</p>');
                $this->session->set('id', $result['result']['id']);
                $this->session->set('role', $result['result']['name']);
                $this->session->set('pseudo', $post->get('pseudo'));
                header('Location: ../public/index.php');
            }
            else {
                $this->session->set('error_login', '<p>Le pseudo ou le mot de passe sont incorrects</p>');
                return $this->view->render('login', [
                    'post'=> $post
                ]);
            }
        }
        return $this->view->render('login');
    }
}

