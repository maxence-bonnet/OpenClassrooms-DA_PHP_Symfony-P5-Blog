<!-- Waiting for Twig -->
<?php $this->title = htmlspecialchars($article->getTitle()); 
    $createdAt = new DateTime(htmlspecialchars($article->getCreatedAt()));
    $createdAt = $createdAt->format('d-m-y H:i');
    $modified = "";
    if($article->getCreatedAt() < $article->getLastModified()){
        $lastModified = new DateTime(htmlspecialchars($article->getLastModified()));
        $lastModified = $lastModified->format('d-m-y H:i');
        $modified = "<span class=\"fs-6 fw-lighter fst-italic\"> (dernière modification le : " . $lastModified . ")</span>";
    }
?>

<p>En construction</p>

<?= $this->session->show('editedArticle'); ?>
<?= $this->session->show('addedComment'); ?>
<?= $this->session->show('editedComment'); ?>
<?= $this->session->show('deletedComment'); ?>

<section class="container bg-light">
    <div class="row">
        <div class="col-12 col-lg-8 p-2 shadow-sm">
            <h2 class="text-center text-md-start"><?= $this->title ?></h2>
            <h5 class="text-center text-md-start"><?= htmlspecialchars($article->getAuthorPseudo()) . " le " . $createdAt . $modified ?></h5>
            <p><?= htmlspecialchars($article->getLede()) ?></p>
            <p><?= htmlspecialchars($article->getContent()) ?></p>
            <a href="../public/index.php?route=editArticle&articleId=<?= htmlspecialchars($article->getId()) ?>" class="btn btn-primary">Modifier cet article</a>
            <a href="../public/index.php?route=deleteArticle&articleId=<?= htmlspecialchars($article->getId()) ?>" class="btn btn-primary">Supprimer cet article</a>     
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


<section class="container ps-5">
    <?php 
    if($article->getAllowComment()) {
    ?>
    <h3 class="text-center text-md-start">Les commentaires :</h3>
    <?php
        foreach($comments as $comment)
        {
            $createdAt = new DateTime(htmlspecialchars($comment->getCreatedAt()));
            $createdAt = $createdAt->format('d-m-y H:i') . " à " . $createdAt->format('H:i');
            if($comment->getLastModified() !== null){
                $createdAt .= " (modifié)";
            }
            ?>
                <div class="row mb-2">
                    <div class="col-6 bg-light shadow-sm">
                        <div class="<?= (isset($post) && ($post->get('id') === $comment->getId())) ? 'alert alert-warning' : '' ?> ">
                            <h6><?= htmlspecialchars($comment->getUserPseudo()) . " le " . $createdAt?></h6>
                            <p><?= nl2br(htmlspecialchars($comment->getContent()))?></p>
                            <a href="../public/index.php?route=editComment&commentId=<?= htmlspecialchars($comment->getId()) ?>" class="btn btn-primary">Modifier ce commentaire</a>
                            <a href="../public/index.php?route=deleteComment&commentId=<?= htmlspecialchars($comment->getId()) ?>" class="btn btn-primary">Supprimer ce commentaire</a>                                
                        </div>
                    </div>                
                </div>
            <?php                
        }
    ?>
    <div class="row">
        <?php
        if (1){ //$this->session->get('pseudo')
            ?>
                <div class="col-6">
                    <h4 class="text-center text-md-start"><?= isset($post) ? 'Modifier le commentaire' : 'Ajouter un commentaire' ?></h4>
                    <form method="post" action="../public/index.php?route=<?= isset($post) ? "editComment&commentId=" . htmlspecialchars($comment->getId()) : "addComment&articleId=" . htmlspecialchars($article->getId()); ?>">
                        <div class="row">
                            <div class="col-2">
                            <h5> Maxence </h5>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="content">Votre commentaire :</label>
                                    <textarea class="form-control <?= isset($errors['content']) ? "is-invalid" : ''; ?>" id="commentContent" name="content" aria-describedby="commentContentHelp" style="height: 120px" placeholder="C'est énorme j'adore !"><?= isset($post) ? htmlspecialchars($post->get('content')) : ''; ?></textarea>
                                    <?= isset($errors['content']) ? $errors['content'] : ''; ?>
                                    <div id="commentContentHelp" class="form-text">Ce commentaire sera soumis à relecture avant publication.<br>500 caractères maximum : 0/500</div>
                                </div>
                                <input type="hidden" name="answer_to" value="">
                                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Valider">
                            </div>
                        </div>
                    </form>                       
                </div>        
            <?php            
        } else {
            ?>
             <div class="col-6 alert alert-dark" role="alert">
                Vous devez être connecté pour commenter un article.
            </div>
            <?php
        }
        ?>
    </div>
    <?php 
    } else {
        ?>
        <h3 class="text-center text-md-start">Les commentaires sont désactivés pour cet article</h3>
        <?php
    }
    ?>
</section>

