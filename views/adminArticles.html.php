<!-- Waiting for Twig -->
<?php $this->title = "Administration Articles"; ?>

<h1 class="d-flex">
    <?= $this->title ?> 
    <a href="?route=adminComments" class="btn btn-primary ms-auto my-auto">Les commentaires</a>
    <a href="?route=adminUsers" class="btn btn-primary mx-2 my-auto">Les utilisateurs</a>
</h1>

<p>En construction</p>

<?= $this->session->show('updatedArticleStatus'); ?>

<section class="container bg-light">
    <h2>Tous les Articles </h2>
    <div class="row">
        <div class="col-md-6">
            <form action="" method="get" class="mb-3">
                <input type="hidden" name="route" value="adminArticles">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="q">Article contenant : </label>
                    <input type="text" class="form-control" name="q" id="q" placeholder="..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']): '';?>">                   
                    
                    <label class="input-group-text ms-2" for="author">De l'auteur : </label>
                    <input type="text" class="form-control" name="author" id="author" placeholder="..." value="<?= isset($_GET['author']) ? htmlspecialchars($_GET['author']): '';?>"> 
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="afterDatetime">Après : </label>
                    <input type="datetime-local" class="form-control" name="afterDatetime" id="afterDatetime" value="<?= isset($_GET['afterDatetime']) ? htmlspecialchars($_GET['afterDatetime']) : ''?>">
                    <label class="input-group-text ms-2" for="beforeDatetime">Avant : </label>
                    <input type="datetime-local" class="form-control" name="beforeDatetime" id="beforeDatetime" value="<?= isset($_GET['beforeDatetime']) ? htmlspecialchars($_GET['beforeDatetime']) : ''?>">                     
                </div>
                <div class="input-group mb-3">
                        <div class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="published" id="published" <?= isset($_GET['published']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="published"> Publiés </label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="private" id="private" <?= isset($_GET['private']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="private"> Privés </label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="standby" id="standby" <?= isset($_GET['standby']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="standby"> En attente avant publication </label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allArticleStatus" id="allArticleStatus" <?= isset($_GET['allArticleStatus']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="allArticleStatus"> Tous </label>
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
                    </select>     
                </div>
                <button class="btn btn-primary">Rechercher</button>
            </form>         
        </div>
    </div>
    <div class="row">
        <div class="col">
        <h5><?= $pageArticlesCount ?> / <?= $totalArticlesCount ?> Résultat(s) - Page <?= $page ?> / <?= $pages > 0 ? $pages : '1' ?></h5>
            <table class="table table-striped table-hover table-dark">
                <thead>
                    <tr>                    
                        <td> Titre </td>
                        <td> Auteur </td>
                        <td> Date de création </td>
                        <td> Modifier L'article </td>
                        <td> Statut </td>
                        <td> Changer le statut </td>
                        <td> Supprimer </td>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($articles as $article){
                        ?>
                            <tr>
                                <td>
                                    <a href="?route=article&articleId=<?= htmlspecialchars($article->getId()) ?>"><?= htmlspecialchars($article->getTitle()) ?></a>
                                </td>
                                <td>
                                    <a href="?route=profile&userId=<?= htmlspecialchars($article->getAuthorId()) ?>"><?= htmlspecialchars($article->getAuthorPseudo()) ?></a>
                                </td>
                                <td>
                                    <?= $article->getFormatedDate($article->getCreatedAt()) ?>
                                </td>
                                <td>
                                    <a href="?route=editArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Modifier</a> 
                                </td>
                                <td>
                                    <?= htmlspecialchars($article->getStatusName()) ?>
                                </td>
                                <td>
                                    <?php if((int)$article->getStatusId() === 1): ?>
                                        <a href="?route=updateArticleStatus&articleId=<?= htmlspecialchars($article->getId()) ?>&statusId=2"> Publier en privé </a>
                                        | 
                                        <a href="?route=updateArticleStatus&articleId=<?= htmlspecialchars($article->getId()) ?>&statusId=3"> Suspendre la publication </a>
                                    <?php elseif((int)$article->getStatusId() === 2): ?>
                                        <a href="?route=updateArticleStatus&articleId=<?= htmlspecialchars($article->getId()) ?>&statusId=1"> Publier en public </a>
                                        | 
                                        <a href="?route=updateArticleStatus&articleId=<?= htmlspecialchars($article->getId()) ?>&statusId=3"> Suspendre la publication </a>
                                    <?php elseif((int)$article->getStatusId() === 3): ?>
                                        <a href="?route=updateArticleStatus&articleId=<?= htmlspecialchars($article->getId()) ?>&statusId=1"> Publier en public </a>
                                        | 
                                        <a href="?route=updateArticleStatus&articleId=<?= htmlspecialchars($article->getId()) ?>&statusId=2"> Publier en privé </a>
                                    <?php endif ?>                             
                                </td>
                                <td>
                                    <a href="?route=deleteArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Supprimer</a>
                                </td>
                            </tr>
                        <?php
                    }
                    if(empty($articles)){
                        ?>
                        <td colspan="7" class="text-center">
                            Aucun article correspondant à la recherche.
                        </td>
                        <?php
                    }
                ?>
                </tbody>
            </table>
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
</section>