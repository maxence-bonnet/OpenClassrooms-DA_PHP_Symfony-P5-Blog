<?php

namespace App\src\controller\backcontroller;

use App\config\Parameter;
use App\src\controller\Controller;

class BackCommentController extends BackController
{
    public function viewSingleComment(int $commentId)
    {
        $this->checkAdmin();

        $comment = $this->commentDAO->getComment($commentId);
        if($comment){
            $data['comment'] = $comment;
            $data['title'] = 'Commentaire de : ' . $comment->getUserPseudo();
            return $this->view->renderTwig('singleComment', $data);
        }
        $this->session->addMessage('danger', 'Le commentaire recherché n\'existe pas / plus');
        $this->http->dynamicRedirect('?route=adminComments',$this->session);  
    }

    public function adminEditComment(Parameter $post, $commentId)
    {
        $this->checkAdmin();
        
        $comment = $this->commentDAO->getComment($commentId);

        if($post->get('submit')){
            $errors = $this->validation->validate($post, 'Comment');
            if(!$errors){
                $validated = 1;
                $this->commentDAO->editComment(htmlspecialchars($post->get('comment')), $commentId, $validated);
                $this->session->addMessage('success', 'Le commentaire a bien été modifié et publié');  
                $this->http->dynamicRedirect('?route=adminComments',$this->session);                
            }
            $data['errors'] = $errors ;
        } else {
            $post->set('comment', $comment->getContent());
            $post->set('id', $comment->getId());
        }

        $data['title'] = 'Modifier le commentaire';
        $data['post'] = $post;

        return $this->view->renderTwig('adminEditComment', $data);
    }

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