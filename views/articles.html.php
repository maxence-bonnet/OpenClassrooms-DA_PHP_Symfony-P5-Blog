<!-- Waiting for Twig -->
<?php $this->title = "Les Articles"; ?>

<h1><?= $this->title ?></h1>

<p>En construction</p>

<?= $this->session->show('addedArticle'); ?>
<?= $this->session->show('deletedArticle'); ?>
<?= $this->session->show('unfoundArticle'); ?>
<?= $this->session->show('deletedComment'); ?>


<div class="row">
        <?php
        foreach($articles as $article)
        {
            $createdAt = new DateTime(htmlspecialchars($article->getCreatedAt()));
            $createdAt = $createdAt->format('d-m-y H:i');
            ?>
                <div class="col-sm-4">
                    <div class="card my-0">
                        <div class="card-body">
                            <h5 class="card-title"><a href="../public/index.php?route=article&articleId=<?= htmlspecialchars($article->getId()) ?>"><?= htmlspecialchars($article->getTitle()) ?></a></h5>
                            <p class="card-text"><?= htmlspecialchars($article->getLede()) ?></p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Créé le : <?= $createdAt ?> | Statut : <?= htmlspecialchars($article->getStatusName()) ?></small>
                        </div>
                    </div>
                </div>
            <?php
        }
        ?>
</div>
