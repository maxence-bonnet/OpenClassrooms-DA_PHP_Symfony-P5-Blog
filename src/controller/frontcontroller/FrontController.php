<?php

namespace App\src\controller\frontcontroller;

use App\src\controller\Controller;
use App\config\Parameter;

class FrontController extends Controller
{
    public function home(Parameter $post)
    {
        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'ContactForm');
            if(!$errors){
                $result = $this->mailer->sendContactForm($post);
                if($result){
                    $this->session->addMessage('success','Votre message a bien été envoyé');
                    $this->http->redirect('?');
                }
                $this->session->addMessage('danger','Échec lors de l\'envoi du message<');  
            }
            $data['post'] = $post;
            $data['errors'] = $errors;            
        }
        $data['title'] = 'Accueil';  
        return $this->view->renderTwig('home', $data);
    }
}