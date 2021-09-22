<!-- Waiting for Twig -->
<?php $this->title = "Les Articles"; ?>

<h1><?= $this->title ?></h1>

<p>En construction</p>

<?= $this->session->show('addedArticle'); ?>
<?= $this->session->show('deletedArticle'); ?>
<?= $this->session->show('unfoundArticle'); ?>

<?= $this->session->show('privateArticle'); ?>
<?= $this->session->show('standbyArticle'); ?>

<div class="row">
        <h5><?= $pageArticlesCount ?> / <?= $totalArticlesCount ?> Résultat(s) - Page <?= $page ?> / <?= $pages > 0 ? $pages : '1' ?></h5>
        <?php
        foreach($articles as $article)
        {
            ?>
                <div class="col-sm-4">
                    <div class="card my-2">
                        <div class="card-body">
                            <h5 class="card-title"><a href="?route=article&articleId=<?= htmlspecialchars($article->getId()) ?>"><?= htmlspecialchars($article->getTitle()) ?></a></h5>
                            <p class="card-text"><?= htmlspecialchars($article->getLedeExcerpt()) ?></p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Créé <?= $article->getFormatedDate($article->getCreatedAt()) ?> | Statut : <?= htmlspecialchars($article->getStatusName()) ?></small>
                        </div>
                    </div>
                </div>
            <?php
        }
        ?>
        <div class="d-flex justify-content-between my-4">
            <?php
                if($previousPageUrl){
                    ?>
                    <a href="?<?= $previousPageUrl ?>" class="btn btn-primary">&#10094; Page précédente </a>
                    <?php
                }

                if($nextPageUrl){
                    ?>
                    <a href="?<?= $nextPageUrl ?>" class="btn btn-primary ms-auto"> Page suivante &#10095;</a>
                    <?php                
                }
            ?>
        </div>
</div>