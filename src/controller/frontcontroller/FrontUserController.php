<?php

namespace App\Src\Controller\FrontController;

use App\Config\Parameter;
use App\Src\Constraint\Constraint;
use Exception;

class FrontUserController extends FrontController
{
    public function register(Parameter $post)
    {
        if ($this->session->get('pseudo')) {
            $this->session->set('danger', 'Vous ne pouvez pas vous inscrire en étant déjà connecté');
            $this->http->redirect('?');
        }

        if ($post->get('submit')) {
            $password = $post->get('password1');
            foreach ($post->all() as $key => $value) {
                $post->set($key, htmlentities($value));
            }
            $errors = $this->validation->validate($post, 'User');
            if (!$errors) {
                $post->set('createdAt', date('Y-m-d H:i:s'));
                $post->set('password', password_hash($password,PASSWORD_BCRYPT));
                try {
                    $this->userDAO->register($post);
                } catch (Exception $error) {
                    throw new Exception('Erreur lors de l\'inscription de l\'utilisateur : ' . $error->getMessage());
                }
                $this->session->addMessage('success', 'Inscription réussie');
                $this->http->redirect('?route=login');
            }
            if (isset($errors['missingField'])) {
                $this->session->addMessage('danger','Un ou plusieurs champs manquant.');
            }
            $data['post'] = $post;
            $data['errors'] = $errors;
        }
        $data['title'] = 'Inscription'; 
        return $this->view->renderTwig('register', $data);
    }

    public function login(Parameter $post)
    {
        if (strlen($post->get('pseudo')) > 61 || strlen($post->get('password')) > 121) {
            $this->http->redirect('?');
        }
        
        if ($this->session->get('pseudo')) {
            $this->session->addMessage('danger', 'Vous ne pouvez pas effectuer cette action en étant déjà connecté');
            $this->http->redirect('?');
        }

        if ($post->get('submit')) {
            if (!empty($post->get('pseudo')) && $this->userDAO->pseudoExists($post->get('pseudo'))) {
                try {
                    $result = $this->userDAO->login($post);
                } catch(Exception $error) {
                    throw new Exception('Erreur lors de la connexion de l\'utilisateur : ' . $error->getMessage());
                }
                if ($result) {
                    if (password_verify($post->get('password'), $result['password'])) {
                        if ($result['status_name'] === "banned") {
                            $this->session->addMessage('danger', 'Vous avez été banni du blog');
                        } else {
                            $this->userDAO->updateUserStatus((int)$result['id'], 2); // set connected status
                            $this->session->set('id', $result['id']);
                            $this->session->set('role', $result['role_name']);
                            $this->session->set('pseudo', $post->get('pseudo'));
                            $this->session->set('refreshed', time());
                            $this->session->addMessage('success', 'Vous êtes connecté en tant que ' . $post->get('pseudo'));
                            $this->CSRF(); 
                            $this->http->dynamicRedirect('?',$this->session);
                        }                            
                    }  
                }
            }
            $this->session->addMessage('danger', 'Pseudo ou mot de passe incorrect');
            $data['post'] = $post;
        }
        $data['title'] = 'Connexion';
        return $this->view->renderTwig('login', $data);
    }

    public function logout()
    {
        $this->checkLoggedIn();

        $this->userDAO->logout($this->session->get('id'));

        $this->session->stop();
        $this->session->start();

        $this->session->addMessage('success', 'Vous êtes déconnecté, à bientôt !');

        $this->http->redirect('?');
    }

    public function profile(Parameter $get)
    {
        $this->checkLoggedIn();

        $user = $this->userDAO->getUser((int)$get->get('userId'));
        if (!$user) {
            $this->session->addMessage('danger', 'Utilisateur inexistant');
            $this->http->redirect('?');
        }

        $data['title'] = 'Profil de ' . $user->getPseudo() ;

        $data['commentsCount'] = $this->commentDAO->countComments(['userId' => (int)$user->getId()]);

        $data['articlesCount'] = $this->articleDAO->countArticles(['authorId' => (int)$user->getId()]);

        if ($user->getPseudo() === $this->session->get('pseudo')) {
            $themesList = $this->getThemesList();
            if ($get->get('theme')) {
                if (in_array($get->get('theme'),$themesList) || $get->get('theme') === "default") {
                    if ($get->get('theme') === "default" && $this->session->get('theme')) {
                        $this->session->remove('theme');
                    }
                    $this->session->set('theme', $get->get('theme'));
                    $this->session->addMessage('success','Le thème a été mis à jour : ' . $get->get('theme'));          
                }
            }
            $data['themesList'] = $themesList;
            $data['title'] = 'Profil';
        }
     
        $data['user'] = $user;
        return $this->view->renderTwig('profile', $data);
    }

    private function getThemesList() : array 
    {
        $exclude = ['.','..'];
        $themes = scandir('../public/bootstrap_themes/'); // Codacy doesn't like scandir()
        foreach ($themes as $theme) {
            if (!in_array($theme,$exclude) && preg_match("#^bootstrap-[a-z\-]+\.css$#",$theme)) {                   
                    $themesList[] = preg_replace("#^bootstrap-([a-z\-]+)\.css$#","$1",$theme);
            }
        }
        return isset($themesList) ? $themesList : [];
    }
}