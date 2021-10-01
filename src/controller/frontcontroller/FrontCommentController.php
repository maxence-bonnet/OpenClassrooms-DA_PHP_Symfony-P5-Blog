<?php

namespace App\src\controller\frontcontroller;

use App\config\Parameter;

class FrontCommentController extends FrontController
{
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
                        $this->http->redirect('?route=article&articleId=' . $articleId);
                    }

                    if($this->session->get('role') === "admin" || $this->session->get('role') === "moderator"){
                        $parameters['validated'] = 1;
                        $this->session->addMessage('success', 'Le commentaire a bien été publié');
                    } else {
                        $parameters['validated'] = 0;
                        $this->session->addMessage('success', 'Le commentaire a bien été envoyé (soumis à validation avant publication)');
                    }

                    $this->commentDAO->addComment($parameters);
                    $this->http->redirect('?route=article&articleId=' . $articleId);
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
        $this->http->redirect('?route=article&articleId=' . $articleId);
    }

    public function editComment(Parameter $post, int $commentId)
    {
        $this->checkLoggedIn();

        $comment = $this->commentDAO->getComment($commentId);
        if(!$comment){
            $this->session->addMessage('danger', 'Commentaire inexistant');
            $this->http->redirect('?route=articles');
        }

        $articleId = $comment->getArticleId();

        if($this->session->get('role') !== "admin" && $this->session->get('role') !== "moderator"){
            if($this->session->get('id') !== $comment->getUserId()){
                $this->session->addMessage('danger', 'Vous ne pouvez pas modifier les commentaires d\'autres personnes');
                $this->http->redirect('?route=article&articleId=' . $articleId);
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
                $this->http->redirect('?route=article&articleId=' . $articleId);
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
}