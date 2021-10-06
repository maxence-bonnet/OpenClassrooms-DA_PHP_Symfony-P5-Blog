<?php

namespace App\Src\Controller\FrontController;

use App\Src\Controller\Controller;
use App\Config\Parameter;

class FrontController extends Controller
{
    public function home(Parameter $post)
    {
        if ($post->get('submit')) {
            $errors = $this->validation->validate($post, 'ContactForm');
            if (!$errors) {
                $result = $this->mailer->sendContactForm($post);
                if ($result) {
                    $this->session->addMessage('success','Votre message a bien été envoyé');
                    $this->http->redirect('?');
                }
                $this->session->addMessage('danger','Échec lors de l\'envoi du message');  
            }
            if (isset($errors['missingField'])) {
                $this->session->addMessage('danger','Un ou plusieurs champs manquant.');
            }
            $data['post'] = $post;
            $data['errors'] = $errors;            
        }
        $data['title'] = 'Accueil';  
        return $this->view->renderTwig('home', $data);
    }
}