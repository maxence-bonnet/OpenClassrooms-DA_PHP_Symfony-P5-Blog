<!-- Waiting for Twig -->
<?php $this->title = "Administration Commentaires"; ?>

<h1 class="d-flex">
    <?= $this->title ?> 
    <a href="?route=adminArticles" class="btn btn-primary ms-auto my-auto">Les articles</a>
    <a href="?route=adminUsers" class="btn btn-primary mx-2 my-auto">Les utilisateurs</a>
</h1>

<p>En construction</p>

<?= $this->session->show('deletedComment'); ?>
<?= $this->session->show('adminEditedComment'); ?>
<?= $this->session->show('updatedCommentValidation'); ?>
<?= $this->session->show('unfoundComment'); ?>

<section class="container bg-light">
    <h2>Tous les Commentaires </h2>
    <div class="row">
        <div class="col-md-6">
            <form action="" method="get" class="mb-3">
                <input type="hidden" name="route" value="adminComments">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="q">Commentaire contenant : </label>
                    <input type="text" class="form-control" name="q" id="q" placeholder="..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']): '';?>">                   
                    
                    <label class="input-group-text ms-2" for="author">De l'auteur : </label>
                    <input type="text" class="form-control" name="author" id="author" placeholder="..." value="<?= isset($_GET['author']) ? htmlspecialchars($_GET['author']): '';?>">
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="articleId">De l'article : </label>
                    <select class="form-select" name ="articleId" id="articleId">
                        <option value="">Tous les articles</option>
                        <?php foreach($articlesList as $article) :?>
                            <option value="<?= $article->getId() ?>" <?= isset($_GET['articleId']) && $_GET['articleId'] === $article->getId() ? 'selected' : '' ?>><?= $article->getTitle() ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="afterDatetime">Après : </label>
                    <input type="datetime-local" class="form-control" name="afterDatetime" id="afterDatetime" value="<?= isset($_GET['afterDatetime']) ? htmlspecialchars($_GET['afterDatetime']) : ''?>">
                    <label class="input-group-text ms-2" for="beforeDatetime">Avant : </label>
                    <input type="datetime-local" class="form-control" name="beforeDatetime" id="beforeDatetime" value="<?= isset($_GET['beforeDatetime']) ? htmlspecialchars($_GET['beforeDatetime']) : ''?>">                     
                </div>
                <div class="input-group mb-3">
                        <div class="form-check-inline">
                            <input class="form-check-input" type="radio" name="validated" id="validated" value="validated" <?= isset($_GET['validated']) && $_GET['validated'] === "validated" ? 'checked' : '' ?>>
                            <label class="form-check-label" for="validated"> Validés  </label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="radio" name="validated" id="pending" value="pending" <?= isset($_GET['validated']) && $_GET['validated'] === "pending" ? 'checked' : '' ?>>
                            <label class="form-check-label" for="pending"> Non validés </label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="radio" name="validated" id="all" value="all" <?= isset($_GET['validated']) && $_GET['validated'] === "all" ? 'checked' : '' ?>>
                            <label class="form-check-label" for="all"> Tous </label>
                        </div>                        
                    </div>                
                <div class="input-group mb-3">
                    <label class="input-group-text" for="limit">Nombre de réulstats par page</label>
                    <select  class="form-select me-auto" name="limit" id="limit">
                        <option value="10" <?= isset($_GET['limit']) && $_GET['limit'] === "10" ?'selected':'';?>>10</option>
                        <option value="20" <?= !isset($_GET['limit']) || $_GET['limit'] === "20" ?'selected':'';?>>20</option>
                        <option value="30" <?= isset($_GET['limit']) && $_GET['limit'] === "30" ?'selected':'';?>>30</option>
                        <option value="40" <?= isset($_GET['limit']) && $_GET['limit'] === "40" ?'selected':'';?>>40</option>
                        <option value="50" <?= isset($_GET['limit']) && $_GET['limit'] === "50" ?'selected':'';?>>50</option>
                        <option value="75" <?= isset($_GET['limit']) && $_GET['limit'] === "75" ?'selected':'';?>>75</option>
                        <option value="100"<?= isset($_GET['limit']) && $_GET['limit'] === "100" ?'selected':'';?>>100</option>
                    </select>     
                </div>
                <button class="btn btn-primary">Rechercher</button>
            </form>         
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h5><?= $pageCommentsCount ?> / <?= $totalCommentsCount ?> Résultat(s) - Page <?= $page ?> / <?= $pages > 0 ? $pages : '1' ?></h5>
            <table class="table table-striped table-hover table-dark">
                <thead>
                    <tr>                    
                        <td> Auteur </td>
                        <td> Extrait </td>
                        <td> Date </td>
                        <td> En lien avec l'article </td>
                        <td> Valider / Voir le commentaire </td>
                        <td> Supprimer le commentaire </td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($comments as $comment) : ?>
                        <tr>
                            <td>
                                <a href="?route=profile&userId=<?= (int)$comment->getUserId() ?>"><?= htmlspecialchars($comment->getUserPseudo()) ?></a>
                            </td>
                            <td>
                                <?= htmlspecialchars($comment->getExcerptContent()) ?>
                            </td>
                            <td>
                                <?= $comment->getFormatedDate($comment->getCreatedAt()) ?>
                            </td>
                            <td>
                                <a href="?route=article&articleId=<?= (int)$comment->getArticleId() ?>"><?= htmlspecialchars($comment->getArticleTitle()) ?> </a> 
                            </td>
                            <td>
                                <a href="?route=viewSingleComment&commentId=<?= (int)$comment->getId() ?>"> Voir </a>
                                <?php if(!$comment->getValidated()) : ?>
                                    | <a href="?route=updateCommentValidation&commentId=<?= (int)$comment->getId() ?>&validation=1"> Valider </a>
                                <?php else : ?>
                                    | <a href="?route=updateCommentValidation&commentId=<?= (int)$comment->getId() ?>&validation=0"> Suspendre </a>
                                <?php endif ?>                         
                            </td>
                            <td>
                                <a href="../public/index.php?route=deleteComment&commentId=<?= (int)$comment->getId() ?>"> Supprimer </a>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    <?php if(empty($comments)) : ?>
                        <td colspan="6" class="text-center">
                            Aucun commentaire correspondant à la recherche.
                        </td>
                    <?php endif ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between my-4">
                <?php
                if($previousPageUrl){
                    ?>
                    <a href="?<?= $previousPageUrl ?>" class="btn btn-primary"> Page précédente </a>
                    <?php
                }

                if($nextPageUrl){
                    ?>
                    <a href="?<?= $nextPageUrl ?>" class="btn btn-primary ms-auto"> Page suivante </a>
                    <?php                
                }
                ?>
            </div>
        </div>    
</section>