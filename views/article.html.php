<!-- Waiting for Twig -->
<?php $this->title = htmlspecialchars($article->getTitle()); 
    $modified = "";
    if($article->getCreatedAt() < $article->getLastModified()){
        $lastModified = $article->getFormatedDate($article->getLastModified());
        $modified = "<span class=\"fs-6 fw-lighter fst-italic\"> (dernière modification " . $lastModified . ")</span>";
    }
?>

<p>En construction</p>

<?= $this->session->show('editedArticle'); ?>

<?= $this->session->show('addedComment'); ?>
<?= $this->session->show('editedComment'); ?>

<?= $this->session->show('canNotEditComment'); ?>

<section class="container bg-light">
    <div class="row">
        <div class="col-12 col-lg-8 p-2 shadow-sm">
            <h2 class="text-center text-md-start"><?= $this->title ?></h2>
            <h5 class="text-center text-md-start"><?= htmlspecialchars($article->getAuthorPseudo()) . " " . ($article->getFormatedDate($article->getCreatedAt()) ? : '-> En attente avant publication') . $modified ?></h5>
            <p><?= htmlspecialchars($article->getLede()) ?></p>
            <p><?= htmlspecialchars($article->getContent()) ?></p>
            <!-- <a href="?route=editArticle&articleId=<?= htmlspecialchars($article->getId()) ?>" class="btn btn-primary">Modifier cet article</a> -->
            <!-- <a href="?route=deleteArticle&articleId=<?= htmlspecialchars($article->getId()) ?>" class="btn btn-primary">Supprimer cet article</a>      -->
        </div>
        <div class="col-12 col-lg-4 p-2 px-md-5 shadow-sm">
            <h3 class="text-center text-md-start">A propos de l'auteur(e):</h3>
            <div class="w-50 text-center m-auto">
                <img class="img-thumbnail rounded mx-auto d-block" src="img/author.jpg" alt="image-auteur">
            </div>
            <p>Mgnam laudantium corporis nisi consectetur, non repudiandae natus voluptatum sapiente consequatur dicta. Quidem possimus repudiandae commodi rerum cupiditate!</p>
        </div>            
    </div>
</section>


<section class="container ps-5 mb-3">
    <?php if($article->getAllowComment()) : ?>
        <h3 class="text-center text-md-start">Les commentaires :</h3>
        <!-- COMMENTS -->
        <?php foreach($comments as $comment) : ?>
            <?php if(!$comment->getAnswerTo()) : ?>
            <div class="row mb-2">
                <div class="col-8">
                    <div class="p-2 shadow mb-2 <?= (isset($post) && ($post->get('id') === $comment->getId())) ? 'alert alert-warning' : 'bg-light' ?>" id="comment<?= (int)$comment->getId() ?>">
                        <h6><?= htmlspecialchars($comment->getUserPseudo()) . " " . $comment->getFormatedDate($comment->getCreatedAt())?> <?= $comment->getLastModified() ? '(modifié)' : '' ?></h6>
                        <p><?= nl2br(htmlspecialchars($comment->getContent()))?></p>
                        <div class="d-flex p-2">
                            <?php if($this->session->get('id') && ($this->session->get('id') === $comment->getUserId()) || $this->session->get('role') === "admin" || $this->session->get('role') === "moderator") : ?>          
                                <a href="?route=editComment&commentId=<?= (int)$comment->getId() ?>#commentForm" class="btn btn-primary mx-1">Modifier</a>
                                    <?php if($this->session->get('role') === "admin" || $this->session->get('role') === "moderator") : ?>
                                        <a href="?route=updateCommentValidation&commentId=<?= (int)$comment->getId() ?>&validation=0" class="btn btn-primary mx-1">Masquer</a>
                                    <?php endif ?>
                                <a href="?route=deleteComment&commentId=<?= (int)$comment->getId() ?>" class="btn btn-primary mx-1">Supprimer</a>
                            <?php endif ?>
                            <a href="?route=article&articleId=<?= (int)$article->getId(); ?>&answerTo=<?= (int)$comment->getId() ?>#comment<?= (int)$comment->getId() ?>" class="btn btn-primary ms-auto">Répondre</a>                            
                        </div>
                    </div>
                    <!-- ANSWERS -->
                    <?php foreach($comments as $answer) : ?>
                        <?php if((int)$answer->getAnswerTo() === (int)$comment->getId()) : ?>
                            <div class="row mb-2 d-flex justify-content-end">
                                <div class="col-11">
                                    <div class="p-4 shadow mb-2 <?= (isset($post) && ($post->get('id') === $answer->getId())) ? 'alert alert-warning' : 'bg-light' ?>" id="comment<?= (int)$answer->getId() ?>">
                                        <h6><?= htmlspecialchars($answer->getUserPseudo()) . " " . $answer->getFormatedDate($answer->getCreatedAt())?> <?= $answer->getLastModified() ? '(modifié)' : '' ?></h6>
                                        <p><?= nl2br(htmlspecialchars($answer->getContent()))?></p>
                                        <div class="d-flex p-2">
                                            <?php if($this->session->get('id') && ($this->session->get('id') === $answer->getUserId()) || $this->session->get('role') === "admin" || $this->session->get('role') === "moderator") : ?>          
                                                <a href="?route=editComment&commentId=<?= (int)$answer->getId() ?>#commentForm" class="btn btn-primary mx-1">Modifier</a>
                                                    <?php if($this->session->get('role') === "admin" || $this->session->get('role') === "moderator") : ?>
                                                        <a href="?route=updateCommentValidation&commentId=<?= (int)$answer->getId() ?>&validation=0" class="btn btn-primary mx-1">Masquer</a>
                                                    <?php endif ?>
                                                <a href="?route=deleteComment&commentId=<?= (int)$answer->getId() ?>" class="btn btn-primary mx-1">Supprimer</a>
                                            <?php endif ?>
                                            <a href="?route=article&articleId=<?= (int)$article->getId(); ?>&answerTo=<?= (int)$comment->getId() ?>#comment<?= (int)$comment->getId() ?>" class="btn btn-primary ms-auto">Répondre</a>                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ANSWERS ANSWER FORM -->
                            <?php if(isset($answerTo) && (int)$answerTo === (int)$answer->getId()) : ?>
                                <div class="row mb-2 d-flex justify-content-end">
                                    <div class="col-11">
                                        <div class="py-2 px-5 shadow bg-light">
                                            <form method="post" action="?route=<?= isset($post) ? "editComment&commentId=" . (int)$post->get('id') : "addComment&articleId=" . (int)$article->getId(); ?>" id="answerForm">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="content">Votre réponse :</label>
                                                        <textarea class="form-control <?= isset($errors['content']) ? "is-invalid" : ''; ?>" id="commentContent" name="content" aria-describedby="commentContentHelp" style="height: 120px" placeholder="..."><?= isset($post) ? htmlspecialchars($post->get('content')) : ''; ?></textarea>
                                                        <?= isset($errors['content']) ? $errors['content'] : ''; ?>
                                                        <div id="commentContentHelp" class="form-text">Ce commentaire sera soumis à relecture avant publication.<br>500 caractères maximum : 0/500</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="hidden" name="answerTo" value="<?= isset($answerTo) ? $answerTo : ''?>">
                                                        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Répondre">
                                                    </div>
                                                </div>
                                            </form>                                    
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <!-- END  ANSWERS ANSWER FORM -->
                        <?php endif ?>
                    <?php endforeach ?> 
                    <!-- END ANSWERS -->
                    <!-- ANSWER FORM -->
                    <?php if(isset($answerTo) && (int)$answerTo === (int)$comment->getId()) : ?>
                        <div class="row mb-2 d-flex justify-content-end">
                            <div class="col-11">
                                <div class="py-2 px-5 shadow bg-light">
                                    <form method="post" action="?route=<?= isset($post) ? "editComment&commentId=" . (int)$post->get('id') : "addComment&articleId=" . (int)$article->getId(); ?>" id="answerForm">
                                        <div class="row d-flex justify-content-center">
                                            <div class="mb-3">
                                                <label class="form-label" for="content">Votre réponse :</label>
                                                <textarea class="form-control <?= isset($errors['content']) ? "is-invalid" : ''; ?>" id="commentContent" name="content" aria-describedby="commentContentHelp" style="height: 120px" placeholder="..."><?= isset($post) ? htmlspecialchars($post->get('content')) : ''; ?></textarea>
                                                <?= isset($errors['content']) ? $errors['content'] : ''; ?>
                                                <div id="commentContentHelp" class="form-text">Ce commentaire sera soumis à relecture avant publication.<br>500 caractères maximum : 0/500</div>
                                            </div>
                                            <div class="mb-3">
                                                <input type="hidden" name="answerTo" value="<?= isset($answerTo) ? $answerTo : ''?>">
                                                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Répondre">
                                            </div>
                                        </div>
                                    </form>                                    
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                    <!-- END ANSWER FORM -->
                </div>             
            </div>
            <?php endif ?>
        <?php endforeach ?>
        <!-- END COMMENTS -->         
        <div class="row mb-2">
            <?php if ($this->session->get('pseudo')) : ?>
                <div class="col-6">
                    <div class="p-2 bg-light shadow">
                        <h4 class="text-center text-md-start"><?= isset($post) ? 'Modifier le commentaire' : 'Ajouter un commentaire' ?></h4>
                        <form method="post" action="?route=<?= isset($post) ? "editComment&commentId=" . (int)$post->get('id') : "addComment&articleId=" . (int)$article->getId(); ?>" id="commentForm">
                            <div class="mb-3">
                                <label class="form-label" for="commentContent"><?= $this->session->get('pseudo') ?> :</label>
                                <textarea class="form-control <?= isset($errors['content']) ? "is-invalid" : ''; ?>" id="commentContent" name="content" aria-describedby="commentContentHelp" style="height: 120px" placeholder="C'est énorme j'adore !"><?= isset($post) ? htmlspecialchars($post->get('content')) : ''; ?></textarea>
                                <?= isset($errors['content']) ? $errors['content'] : ''; ?>
                                <div id="commentContentHelp" class="form-text">Ce commentaire sera soumis à relecture avant publication.<br>500 caractères maximum : 0/500</div>
                            </div>
                            <div class="mb-3 d-flex justify-content-center">
                                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Valider">   
                            </div>
                        </form>
                    </div>
                </div>        
            <?php else : ?>
                <div class="col-6 alert alert-dark d-flex" role="alert">
                    Vous devez être connecté pour commenter un article.
                    <a href="?route=login" class="btn btn-primary ms-auto"> Se connecter </a>
                </div>
            <?php endif ?>
        </div>
    <?php  else : ?>
        <h3 class="text-center text-md-start">Les commentaires sont désactivés pour cet article</h3>
    <?php endif ?>
</section>

