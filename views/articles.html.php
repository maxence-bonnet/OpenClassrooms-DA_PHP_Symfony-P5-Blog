<!-- Waiting for Twig -->
<?php $this->title = "Les Articles"; ?>

<h1><?= $this->title ?></h1>

<p>En construction</p>

<?= $this->session->show('addedArticle'); ?>
<?= $this->session->show('deletedArticle'); ?>
<?= $this->session->show('unfoundArticle'); ?>

<div class="row">
        <?php
        foreach($articles as $article)
        {
            ?>
                <div class="col-sm-4">
                    <div class="card my-2">
                        <div class="card-body">
                            <h5 class="card-title"><a href="../public/index.php?route=article&articleId=<?= htmlspecialchars($article->getId()) ?>"><?= htmlspecialchars($article->getTitle()) ?></a></h5>
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
            if($page > 1){
                ?>
                <a href="?route=articles&page=<?= $page - 1 ?>" class="btn btn-primary">&#10094; Page précédente </a>
                <?php
            }
            if($page < $pages && $pages !== 1){
                ?>
                <a href="?route=articles&page=<?= $page + 1 ?>" class="btn btn-primary ms-auto"> Page suivante &#10095;</a>
                <?php                
            }
            ?>
        </div>
</div>