<?php

namespace App\src\controller\frontcontroller;

use App\config\Parameter;
use App\src\constraint\Constraint;

class FrontUserController extends FrontController
{
    public function register(Parameter $post)
    {
        if($this->session->get('pseudo')){
            $this->session->set('danger', 'Vous ne pouvez pas vous inscrire en étant déjà connecté');
            $this->http->redirect('?');
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
                $this->http->redirect('?route=login');
            }
            $data['post'] = $post;
            $data['errors'] = $errors;
        }
        $data['title'] = 'Inscription'; 
        return $this->view->renderTwig('register', $data);
    }

    public function login(Parameter $post)
    {
        if($this->session->get('pseudo')){
            $this->session->addMessage('danger', 'Vous ne pouvez pas effectuer cette action en étant déjà connecté');
            $this->http->redirect('?');
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
                        if($this->session->get('previousURI')){
                            $this->http->redirect($this->session->use('previousURI'));
                        }                   
                    }
                    $this->http->redirect('?');
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
        if(!$user){
            $this->session->addMessage('danger', 'Utilisateur inexistant');
            $this->http->redirect('?');
        }

        $data['title'] = 'Profil de ' . $user->getPseudo() ;
        if($user->getPseudo() === $this->session->get('pseudo')){
            $themesList = [];
            $exclude = ['.','..'];
            $themes = scandir('../public/bootstrap_themes/'); // Codacy doesn't like scandir()
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
                $this->http->redirect('?route=profile&userId=' . $this->session->get('id'));
            }
            $data['themesList'] = $themesList;
            $data['title'] = 'Profil';
        }
     
        $data['user'] = $user;
        return $this->view->renderTwig('profile', $data);
    }
}