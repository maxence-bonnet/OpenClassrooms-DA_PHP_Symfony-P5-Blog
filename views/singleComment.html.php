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
                <div class="col-6 bg-light shadow-sm">
                    <div class="<?= (isset($post) && ($post->get('id') === $comment->getId())) ? 'alert alert-warning' : '' ?> ">
                        <h6><?= htmlspecialchars($comment->getUserPseudo()) . " le " . $createdAt?></h6>
                        <p><?= nl2br(htmlspecialchars($comment->getContent()))?></p>
                        <!-- <a href="../public/index.php?route=editComment&commentId=<?= htmlspecialchars($comment->getId()) ?>" class="btn btn-primary">Modifier ce commentaire</a> -->
                        <!-- <a href="../public/index.php?route=deleteComment&commentId=<?= htmlspecialchars($comment->getId()) ?>" class="btn btn-primary">Supprimer ce commentaire</a>                                 -->
                    </div>
                </div>
            </div>
            <a class="btn btn-primary" href="../public/index.php?route=validateComment&commentId=<?= htmlspecialchars($comment->getId()) ?>"> Valider </a>
            <a class="btn btn-primary" href="../public/index.php?route=adminEditComment&commentId=<?= htmlspecialchars($comment->getId()) ?>"> Modifier </a>               
            <a class="btn btn-primary" href="../public/index.php?route=deleteComment&commentId=<?= htmlspecialchars($comment->getId()) ?>"> Supprimer </a>                
        <?php