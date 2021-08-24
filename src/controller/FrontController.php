<?php

namespace App\src\controller;

use App\config\Parameter;

class FrontController extends Controller
{
    // ok
    public function home()
    {
        return $this->view->render('home');
    }

    // ok
    public function articles()
    {
        $articles = $this->articleDAO->getArticles();        
        return $this->view->render('articles', [
            'articles' => $articles
        ]);
    }

    // ok
    public function article(int $articleId)
    {
        $article = $this->articleDAO->getArticle($articleId);
        if($article){
            $comments = "";
            if($article->getAllowComment()) {
                $comments = $this->commentDAO->getCommentsFromArticle($articleId);
            }                        
            return $this->view->render('article', [
                'article' => $article,
                'comments' => $comments
            ]);
        }
        $this->session->set('unfoundArticle', '<div class="alert alert-success">L\'article recherché n\'existe pas</div>');

        header('Location: ../public/index.php?route=articles');
    }


    // ok
    public function addComment(Parameter $post, $articleId)
    {
        $article = $this->articleDAO->getArticle($articleId);
        if($article->getAllowComment()){
            if($post->get('submit')) {
                $errors = $this->validation->validate($post, 'Comment');
                if(!$errors) {
                    $userId = 1; // à enlever, ici pour travaux
                    $this->commentDAO->addComment($post, $articleId, $userId); // à l'avenir : $this->session->get('id')    
                    $this->session->set('addedComment', '<div class="alert alert-success">Le commentaire a bien été ajouté</div>');               
                    header('Location: ../public/index.php?route=article&articleId=' . $articleId);
                    exit();
                }
                $comments = $this->commentDAO->getCommentsFromArticle($articleId);
                return $this->view->render('article', [
                    'article' => $article,
                    'comments' => $comments,
                    'post' => $post,
                    'errors' => $errors
                ]);
            }
        }
        header('Location: ../public/index.php?route=article&articleId=' . $articleId);
    }
}

