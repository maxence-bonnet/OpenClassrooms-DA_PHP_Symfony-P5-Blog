<?php $this->title = "Voir un commentaire"; ?>

<h1><?= $this->title ?></h1>

<section class="container ps-5">
    <h3 class="text-center text-md-start">Le commentaire :</h3>
    <?php
        $createdAt = new DateTime(htmlspecialchars($comment->getCreatedAt()));
        $createdAt = $createdAt->format('d-m-y H:i') . " à " . $createdAt->format('H:i');
        if($comment->getLastModified() !== null){
            $createdAt .= " (modifié)";
        }
        ?>
            <div class="row mb-2">
                <div class="col-6 bg-light shadow">
                    <h6><?= htmlspecialchars($comment->getUserPseudo()) . " le " . $createdAt?></h6>
                    <p><?= nl2br(htmlspecialchars($comment->getContent()))?></p>
                </div>
            </div>
            <?php if(!$comment->getValidated()) : ?>
                <a class="btn btn-primary" href="?route=updateCommentValidation&commentId=<?= (int)$comment->getId() ?>&validation=1"> Valider </a>
            <?php else : ?>
                <a class="btn btn-primary" href="?route=updateCommentValidation&commentId=<?= (int)$comment->getId() ?>&validation=0"> Suspendre </a>
                <a class="btn btn-primary" href="?route=article&articleId=<?= (int)$comment->getarticleId() ?>#comment<?= (int)$comment->getId() ?>"> Voir dans son contexte </a>  
            <?php endif ?>
                <a class="btn btn-primary" href="?route=adminEditComment&commentId=<?= (int)$comment->getId() ?>"> Modifier </a>               
                <a class="btn btn-primary" href="?route=deleteComment&commentId=<?= (int)$comment->getId() ?>"> Supprimer </a>      
        <?php