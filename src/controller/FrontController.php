<?php

namespace App\src\controller;

use App\config\Parameter;
use App\src\constraint\Constraint;
use App\config\HTTP;
use App\src\utils\URL;
use App\src\model\AlertMessage;

class FrontController extends Controller
{

    // ok twig
    public function home(Parameter $post)
    {
        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'ContactForm');
            if(!$errors){
                $result = $this->mailer->sendContactForm($post);
                if($result){
                    $this->session->addMessage('success','Votre message a bien été envoyé');
                    HTTP::redirect('?');
                }
                $this->session->addMessage('danger','Échec lors de l\'envoi du message<');  
            }
            $data['post'] = $post;
            $data['errors'] = $errors;            
        }
        $data['title'] = 'Accueil';  
        return $this->view->renderTwig('home', $data);
    }

    // ok twig
    public function register(Parameter $post)
    {
        if($this->session->get('pseudo')){
            $this->session->set('danger', 'Vous ne pouvez pas vous inscrire en étant déjà connecté');
            HTTP::redirect('?');
        }
        if($post->get('submit')){
            // Special error needing DAO
            if($this->userDAO->pseudoExists($post->get('pseudo'))){
                $post->set('pseudo', Constraint::EXISTING_PSEUDO);
            }
            $errors = $this->validation->validate($post, 'User');
            if(!$errors){
                $this->userDAO->register($post);
                $this->session->addMessage('success', 'Inscription réussie');
                HTTP::redirect('?route=login');
            }
            $data['post'] = $post;
            $data['errors'] = $errors;
        }
        $data['title'] = 'Inscription'; 
        return $this->view->renderTwig('register', $data);
    }

    // ok twig
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
            $data['previousPageURL'] = URL::mergeOn($_GET, ['page' => $page - 1]);
        }

        if($page < $pages && $pages !== 1){
            $data['nextPageURL'] = URL::mergeOn($_GET, ['page' => $page + 1]);
        }

        $data['pages'] = $pages;
        $data['title'] = 'Les Articles';
        $data['get'] = $get;

        return $this->view->renderTwig('articles', $data);
    }

    // ok twig
    public function article(Parameter $get)
    {
        $article = $this->articleDAO->getArticle((int)$get->get('articleId'));
        if($article){
            $this->session->set('previousURL', $_SERVER['REQUEST_URI']);
            if($this->session->get('pseudo')){
                if($article->getStatusName() === "standby" && ($this->session->get('role') !== "admin" && $this->session->get('role') !== "editor")){
                    $this->session->addMessage('danger', 'L\'article recherché est n\'est pas encore publié ou n\'est plus disponnible');
                    HTTP::redirect('?route=articles');                  
                }
            } else {
                if($article->getStatusName() === "private"){
                    $this->session->addMessage('danger', 'L\'article recherché est privé, vous devez vous connecter pour pouvoir le consulter');
                    HTTP::redirect('?route=articles');
                } elseif($article->getStatusName() === "standby"){
                    $this->session->addMessage('danger', 'L\'article recherché est n\'est pas encore publié ou n\'est plus disponnible');
                    HTTP::redirect('?route=articles');                  
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
        HTTP::redirect('?route=articles');
    }

    // ok twig
    public function addComment(Parameter $post, int $articleId)
    {
        $this->checkLoggedIn();

        $article = $this->articleDAO->getArticle($articleId);
        if($article && $article->getAllowComment()){
            if($post->get('submit')) {
                $errors = $this->validation->validate($post, 'Comment');
                if(!$errors) {
                    $parameters['userId'] = $this->session->get('id');
                    $parameters['articleId'] = $articleId;
                    
                    if($post->get('answerTo')){
                        $parameters['answerTo'] = (int)$post->get('answerTo');
                        $parameters['content'] = htmlspecialchars($post->get('answer'));
                    } else {
                        $parameters['content'] = htmlspecialchars($post->get('comment'));
                    }

                    if(empty($parameters['content'])){
                        $this->session->addMessage('danger', 'Erreur lors de l\'ajout du commentaire');
                        HTTP::redirect('?route=article&articleId=' . $articleId);
                    }

                    if($this->session->get('role') === "admin" || $this->session->get('role') === "moderator"){
                        $parameters['validated'] = 1;
                        $this->session->addMessage('success', 'Le commentaire a bien été publié');
                    } else {
                        $parameters['validated'] = 0;
                        $this->session->addMessage('success', 'Le commentaire a bien été envoyé (soumis à validation avant publication)');
                    }

                    $this->commentDAO->addComment($parameters);
                    HTTP::redirect('?route=article&articleId=' . $articleId);
                }
                $comments = $this->commentDAO->getComments([
                    'articleId' => $articleId,
                    'validated' => "validated"
                ]);

                if($post->get('answerTo')){
                    $data['answerTo'] = (int)$post->get('answerTo');
                }
                $data['article'] = $article;
                $data['title'] = htmlspecialchars($article->getTitle());
                $data['comments'] = $comments;
                $data['post'] = $post;
                $data['errors'] = $errors;
                
                return $this->view->renderTwig('article', $data);
            }
        }
        HTTP::redirect('?route=article&articleId=' . $articleId);
    }

    // ok twig
    public function editComment(Parameter $post, int $commentId)
    {
        $this->checkLoggedIn();

        $comment = $this->commentDAO->getComment($commentId);
        if(!$comment){
            $this->session->addMessage('danger', 'Commentaire inexistant');
            HTTP::redirect('?route=articles');
        }

        $articleId = $comment->getArticleId();

        if($this->session->get('role') !== "admin" && $this->session->get('role') !== "moderator"){
            if($this->session->get('id') !== $comment->getUserId()){
                $this->session->addMessage('danger', 'Vous ne pouvez pas modifier les commentaires d\'autres personnes');
                HTTP::redirect('?route=article&articleId=' . $articleId);
            }
        }

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Comment');
            if (!$errors){
                if($this->session->get('role') === "admin" || $this->session->get('role') === "moderator"){
                    $validated = 1;
                    $this->session->addMessage('success', 'Le commentaire a bien été modifié');
                } else {
                    $validated = 0;
                    $this->session->addMessage('success', 'Le commentaire a bien été modifié (en attente d\'une nouvelle validation avant publication)');
                }
                $content = htmlspecialchars($post->get('comment'));
                $this->commentDAO->editComment($content, $commentId, $validated);
                HTTP::redirect('?route=article&articleId=' . $articleId);
            }
            $data['errors'] = $errors;
        }

        $article = $this->articleDAO->getArticle($articleId);
        $comments = $this->commentDAO->getComments([
            'articleId' => $articleId,
            'validated' => "validated"
        ]);

        $post->set('id', $comment->getId());
        if(!$post->get('comment')){
            $post->set('comment', $comment->getContent());
        }
        
        $data['post'] = $post;
        $data['article'] = $article;
        $data['comments'] = $comments;
        $data['title'] = $article->getTitle();

        return $this->view->renderTwig('article', $data);
    }

    // ok twig
    public function login(Parameter $post)
    {
        if($this->session->get('pseudo')){
            $this->session->addMessage('danger', 'Vous ne pouvez pas effectuer cette action en étant déjà connecté');
            HTTP::redirect('?');
        }

        if($post->get('submit')){
            if($this->userDAO->pseudoExists($post->get('pseudo'))){
                $result = $this->userDAO->login($post);
                if($result && $result['passwordValid']) {
                    if($result['result']['status_name'] === "banned"){
                        $this->session->addMessage('danger', 'Vous avez été banni du blog');
                    } else {
                        $this->session->set('id', $result['result']['id']);
                        $this->session->set('role', $result['result']['role_name']);
                        $this->session->set('pseudo', $post->get('pseudo'));
                        $this->session->addMessage('success', 'Vous êtes connecté en tant que ' . $post->get('pseudo'));     
                        if($this->session->get('previousURL')){
                            HTTP::redirect($this->session->use('previousURL'));
                        }                   
                    }
                    HTTP::redirect('?');
                }
            }
            $this->session->addMessage('danger', 'Pseudo ou mot de passe incorrect');
            $data['post'] = $post;
        }
        $data['title'] = 'Connexion';
        return $this->view->renderTwig('login', $data);
    }

    // ok twig
    public function logout()
    {
        $this->checkLoggedIn();

        $this->userDAO->logout($this->session->get('id'));

        $this->session->stop();
        $this->session->start();

        $this->session->addMessage('success', 'Vous êtes déconnecté, à bientôt !');

        HTTP::redirect('?');
    }

    public function profile(Parameter $get)
    {
        $this->checkLoggedIn();

        $user = $this->userDAO->getUser((int)$get->get('userId'));
        if(!$user){
            $this->session->addMessage('danger', 'Utilisateur inexistant');
            HTTP::redirect('?');
        }
       
        $themesList = [];
        $exclude = ['.','..'];
        $themes = scandir('../public/bootstrap_themes/');
        foreach ($themes as $theme) {
            if (!in_array($theme,$exclude) && preg_match("#^bootstrap-[a-z]+\.min\.css$#",$theme)){                   
                    $themesList[] = preg_replace("#^bootstrap-([a-z]+)\.min\.css$#","$1",$theme);
                }
        }

        if($get->get('theme')){
            if(in_array($get->get('theme'),$themesList) || $get->get('theme') === "default"){
                if($get->get('theme') === "default" && $this->session->get('theme')){
                    $this->session->remove('theme');
                } else {
                    $this->session->set('theme', $get->get('theme'));
                } 
                $this->session->addMessage('success','Le thème a été mis à jour : ' . $get->get('theme'));          
            }
        }

        $data['title'] = 'Profil de ' . $user->getPseudo() ;
        if($user->getPseudo() === $this->session->get('pseudo')){
            $data['title'] = 'Profil';
        }     
        $data['user'] = $user;
        $data['themesList'] = $themesList;
        return $this->view->renderTwig('profile', $data);
    }
}

