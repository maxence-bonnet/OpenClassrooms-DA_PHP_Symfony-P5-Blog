<?php

namespace App\Src\Controller\BackController;

use App\Config\Parameter;
use App\Src\Controller\Controller;

class BackCommentController extends BackController
{
    public function viewSingleComment(int $commentId)
    {
        $comment = $this->commentDAO->getComment($commentId);
        if (!$comment) {
            $this->session->addMessage('danger', 'Le commentaire recherché n\'existe pas / plus');  
            $this->http->dynamicRedirect('?route=adminComments',$this->session);              
        }

        $this->data['comment'] = $comment;
        $this->data['title'] = 'Commentaire de : ' . $comment->getUserPseudo();
        return $this->view->renderTwig('singleComment', $this->data);
    }

    public function adminEditComment(Parameter $post,int $commentId)
    {        
        $comment = $this->commentDAO->getComment($commentId);
        if (!$comment) {
            $this->session->addMessage('danger', 'Le commentaire recherché n\'existe pas / plus');  
            $this->http->dynamicRedirect('?route=adminComments',$this->session);              
        }

        if ($post->get('submit') && $post->get('token')) {

            $this->checkToken((string)$post->get('token'));

            $errors = $this->validation->validate($post, 'Comment');
            if (!$errors) {
                $validated = 1;
                $lastModified = date('Y-m-d H:i:s');
                $this->commentDAO->editComment(htmlspecialchars($post->get('comment')), $commentId, $lastModified, $validated);
                $this->session->addMessage('success', 'Le commentaire a bien été modifié et publié');  
                $this->http->dynamicRedirect('?route=adminComments',$this->session);                
            }
            if (isset($errors['missingField'])) {
                $this->session->addMessage('danger','Un ou plusieurs champs manquant.');
            }
            $this->data['errors'] = $errors ;
        } else {
            $post->set('comment', $comment->getContent());
            $post->set('id', $comment->getId());
        }

        $this->data['title'] = 'Modifier le commentaire';
        $this->data['post'] = $post;

        return $this->view->renderTwig('adminEditComment', $this->data);
    }

    public function updateCommentValidation(Parameter $get)
    {
        if ($get->get('commentId') && $get->get('token')) {

            $this->checkToken((string)$get->get('token'));

            $validated = (int)$get->get('validation');

            if ($validated === 1 || $validated === 0) {
                $this->commentDAO->updateCommentValidation((int)$get->get('commentId'), $validated);
                $message = $validated ? 'validé' : 'suspendu';
                $this->session->addMessage('success', 'Le commentaire a bien été ' . $message);            
            }
        }
        $this->http->dynamicRedirect('?route=adminComments',$this->session);
    }

    public function deleteComment(Parameter $get)
    {
        if ($get->get('commentId') && $get->get('token')) {

            $this->checkToken((string)$get->get('token'));

            $commentId = (int)$get->get('commentId');

            if ($this->commentDAO->getComment($commentId)) {
                $this->commentDAO->deleteComment($commentId);
                $this->session->addMessage('success', 'Le commentaire a bien été supprimé');
            } else {
                $this->session->addMessage('danger', 'Le commentaire à supprimer n\'existe pas / plus');
            }
        }
        $this->http->dynamicRedirect('?route=adminComments',$this->session);
    }
}