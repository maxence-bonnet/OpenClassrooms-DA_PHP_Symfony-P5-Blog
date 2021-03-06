<?php

namespace App\Src\Controller\FrontController;

use App\Config\Parameter;

class FrontCommentController extends FrontController
{
    public function addComment(Parameter $post, int $articleId)
    {
        $this->checkLoggedIn();

        $article = $this->articleDAO->getArticle($articleId);
        if ($article && $article->getAllowComment()) {
            if ($post->get('submit') && $post->get('token')) {

                $tokenState = $this->checkToken((string)$post->get('token'), ['saveMode' => 1]);

                $errors = $this->validation->validate($post, 'Comment');
                if (!$errors && $tokenState !== 'expired') {
                    $parameters['userId'] = (int)$this->session->get('id');
                    $parameters['articleId'] = $articleId;
                    
                    if ($post->get('answerTo')) {
                        $parameters['answerTo'] = (int)$post->get('answerTo');
                        $parameters['content'] = htmlspecialchars($post->get('answer'));
                    } else {
                        $parameters['answerTo'] = null;
                        $parameters['content'] = htmlspecialchars($post->get('comment'));
                    }

                    if ($this->session->get('role') === "admin" || $this->session->get('role') === "moderator") {
                        $parameters['validated'] = 1;
                        $this->session->addMessage('success', 'Le commentaire a bien été publié');
                    } else {
                        $parameters['validated'] = 0;
                        $this->session->addMessage('success', 'Le commentaire a bien été envoyé (soumis à validation avant publication)');
                    }
                    
                    $parameters['createdAt'] = date('Y-m-d H:i:s');
                    $this->commentDAO->addComment($parameters);
                    $this->http->redirect('?route=article&articleId=' . $articleId);
                }
                if (isset($errors['missingField'])) {
                    $this->session->addMessage('danger','Un ou plusieurs champs manquant.');
                }
                $post->set('new', true);
                $comments = $this->commentDAO->getComments([
                    'articleId' => $articleId,
                    'validated' => "validated"
                ]);

                if ($post->get('answerTo')) {
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
        if (!$comment) {
            $this->session->addMessage('danger', 'Commentaire inexistant');
            $this->http->redirect('?route=articles');
        }

        $articleId = $comment->getArticleId();

        if ($this->session->get('role') !== "admin" && $this->session->get('role') !== "moderator") {
            if ($this->session->get('id') !== $comment->getUserId()) {
                $this->session->addMessage('danger', 'Vous ne pouvez pas modifier les commentaires d\'autres personnes');
                $this->http->redirect('?route=article&articleId=' . $articleId);
            }
        }

        if ($post->get('submit') && $post->get('token')) {

            $tokenState = $this->checkToken((string)$post->get('token'), ['saveMode' => 1]);

            $errors = $this->validation->validate($post, 'Comment');
            if (!$errors && $tokenState !== 'expired') {
                if ($this->session->get('role') === "admin" || $this->session->get('role') === "moderator") {
                    $validated = 1;
                    $this->session->addMessage('success', 'Le commentaire a bien été modifié');
                } else {
                    $validated = 0;
                    $this->session->addMessage('success', 'Le commentaire a bien été modifié (en attente d\'une nouvelle validation avant publication)');
                }
                $content = htmlspecialchars($post->get('comment'));
                $lastModified = date('Y-m-d H:i:s');
                $this->commentDAO->editComment($content, $commentId, $lastModified, $validated);
                $this->http->redirect('?route=article&articleId=' . $articleId);
            }
            if (isset($errors['missingField'])) {
                $this->session->addMessage('danger','Un ou plusieurs champs manquant.');
            }
            $data['errors'] = $errors;
        }

        $article = $this->articleDAO->getArticle($articleId);
        $comments = $this->commentDAO->getComments([
            'articleId' => $articleId,
            'validated' => 'validated',
            'orderBy' => [
                'column' => 'c.created_at',
                'order' => 'ASC'
            ]
        ]);

        $post->set('id', $comment->getId());
        if (!$post->get('comment')) {
            $post->set('comment', $comment->getContent());
        }
        
        $data['post'] = $post;
        $data['article'] = $article;
        $data['comments'] = $comments;
        $data['title'] = $article->getTitle();

        return $this->view->renderTwig('article', $data);
    }

    public function deleteComment(Parameter $get)
    {
        $this->checkLoggedIn();

        if ($get->get('commentId') && $get->get('token')) {

            $comment = $this->commentDAO->getComment((int)$get->get('commentId'));

            if ($comment) {

                if ($this->session->get('roleId') === 1 || $this->session->get('roleId') === 4 || $this->session->get('id') === $comment->getUserId()) {

                    $this->checkToken((string)$get->get('token'));

                    $this->commentDAO->deleteComment((int)$get->get('commentId'));
                    $this->session->addMessage('success', 'Le commentaire a bien été supprimé');                    
                }

            } else {
                $this->session->addMessage('danger', 'Le commentaire à supprimer n\'existe pas / plus');
            }
        }
        $this->http->dynamicRedirect('?route=adminComments',$this->session);     
    }
}