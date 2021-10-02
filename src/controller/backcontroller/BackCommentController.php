<?php

namespace App\src\controller\backcontroller;

use App\config\Parameter;
use App\src\controller\Controller;

class BackCommentController extends BackController
{
    // ok QBuilder
    public function viewSingleComment(int $commentId)
    {
        $this->checkAdmin();

        $comment = $this->commentDAO->getComment($commentId);
        if(!$comment){
            $this->session->addMessage('danger', 'Le commentaire recherché n\'existe pas / plus');  
            $this->http->dynamicRedirect('?route=adminComments',$this->session);              
        }

        $this->data['comment'] = $comment;
        $this->data['title'] = 'Commentaire de : ' . $comment->getUserPseudo();
        return $this->view->renderTwig('singleComment', $this->data);
    }

    // ok QBuilder
    public function adminEditComment(Parameter $post, $commentId)
    {
        $this->checkAdmin();
        
        $comment = $this->commentDAO->getComment($commentId);
        if(!$comment){
            $this->session->addMessage('danger', 'Le commentaire recherché n\'existe pas / plus');  
            $this->http->dynamicRedirect('?route=adminComments',$this->session);              
        }

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Comment');
            if(!$errors){
                $validated = 1;
                $lastModified = date('Y-m-d H:i:s');
                $this->commentDAO->editComment(htmlspecialchars($post->get('comment')), $commentId, $lastModified, $validated);
                $this->session->addMessage('success', 'Le commentaire a bien été modifié et publié');  
                $this->http->dynamicRedirect('?route=adminComments',$this->session);                
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

    // ok QBuilder
    public function updateCommentValidation(int $commentId, int $validated)
    {
        $this->checkAdmin();
        
        if($validated === 1 || $validated === 0){
            $this->commentDAO->updateCommentValidation($commentId, $validated);
            $message = $validated ? 'validé' : 'suspendu';
            $this->session->addMessage('success', 'Le commentaire a bien été ' . $message);            
        }
        $this->http->dynamicRedirect('?route=adminComments',$this->session);
    }

    // ok QBuilder
    public function deleteComment(int $commentId)
    {
        $this->checkAdmin();

        if($this->commentDAO->getComment($commentId)){
            $this->commentDAO->deleteComment($commentId);
            $this->session->addMessage('success', 'Le commentaire a bien été supprimé');
        } else {
            $this->session->addMessage('danger', 'Le commentaire à supprimer n\'existe pas / plus');
        }
        $this->http->dynamicRedirect('?route=adminComments',$this->session);
    }
}