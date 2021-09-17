<!-- Waiting for Twig -->
<?php $this->title = "Administration"; ?>

<h1><?= $this->title ?></h1>

<p>En construction</p>

<?= $this->session->show('deletedComment'); ?>
<?= $this->session->show('adminEditedComment'); ?>
<?= $this->session->show('updatedArticle'); ?>
<?= $this->session->show('deletedArticle'); ?>

<section class="container bg-light">
    <h2 class="d-flex">Commentaires en attente de validation <a href="?route=adminComments" class="btn btn-primary ms-auto">Section Commentaires</a></h2>
    <div class="row">
        <div class="col">
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
                <?php
                    foreach($comments as $comment){
                        ?>
                            <tr>
                                <td>
                                    <a href="?route=profile&userId=<?= htmlspecialchars($comment->getUserId()) ?>"><?= htmlspecialchars($comment->getUserPseudo()) ?></a>
                                </td>
                                <td>
                                    
                                    <?= htmlspecialchars($comment->getExcerptContent()) ?>
                                </td>
                                <td>
                                    <?= $comment->getFormatedDate($comment->getCreatedAt()) ?>
                                </td>
                                <td>
                                    <a href="?route=article&articleId=<?= htmlspecialchars($comment->getArticleId()) ?>"><?= htmlspecialchars($comment->getArticleTitle()) ?> </a> 
                                </td>
                                <td>
                                    <a href="?route=updateCommentValidation&commentId=<?= htmlspecialchars($comment->getId()) ?>&validation=1"> Valider </a> |
                                    <a href="?route=viewSingleComment&commentId=<?= htmlspecialchars($comment->getId()) ?>"> Voir </a>                            
                                </td>
                                <td>
                                    <a href="?route=deleteComment&commentId=<?= htmlspecialchars($comment->getId()) ?>"> Supprimer </a>
                                </td>
                            </tr>
                        <?php
                    }
                    if(empty($comments)){
                        ?>
                        <td colspan="6" class="text-center">
                            Aucun nouveau commentaire à valider.
                        </td>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>    
</section>

<section class="container bg-light">
    <h2 class="d-flex">Les articles <a href="?route=adminArticles" class="btn btn-primary ms-auto">Section Articles</a></h2>
    <div class="row">
        <div class="col">
            <table class="table table-striped table-hover table-dark">
                <thead>
                    <tr>                    
                        <td> Titre </td>
                        <td> Date de création </td>
                        <td> Modifier L'article </td>
                        <td> Statut </td>
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
                                    <?= $article->getFormatedDate($article->getCreatedAt()) ?>
                                </td>
                                <td>
                                    <a href="?route=editArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Modifier</a> 
                                </td>
                                <td>
                                    <?= htmlspecialchars($article->getStatusName()) ?> 
                                </td>
                                <td>
                                    <a href="?route=deleteArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Supprimer</a>
                                </td>
                            </tr>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="container bg-light">
    <h2 class="d-flex">Les utilisateurs <a href="?route=adminUsers" class="btn btn-primary ms-auto">Section Utilisateurs</a></h2>
    <div class="row">
        <div class="col">
            <table class="table table-striped table-hover table-dark">
                <thead>
                    <tr>                    
                        <td> Pseudo </td>
                        <td> Date d'inscription</td>
                        <td> Role </td>
                        <td> Score </td>
                        <td> Statut </td>
                        <td> Changer le role </td>
                        <td> Supprimer </td>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($users as $user){
                        ?>
                            <tr>
                                <td>
                                    <a href="?route=profile&userId=<?= htmlspecialchars($user->getId()) ?>"><?= htmlspecialchars($user->getPseudo()) ?></a>
                                </td>
                                <td>
                                    <?= $user->getFormatedDate($user->getCreatedAt()) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($user->getRoleName()) ?>
                                </td>
                                <td>
                                    <?= (int)$user->getScore() ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($user->getStatusName()) ?>
                                </td>
                                <td>
                                    <?php if((int)$user->getRoleId() === 2): ?>
                                        <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=3"> Éditeur </a>
                                        | 
                                        <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=4"> Modérateur </a>
                                    <?php elseif((int)$user->getRoleId() === 3): ?>
                                        <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=2"> Utilisateur </a>
                                        | 
                                        <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=4"> Modérateur </a>
                                    <?php elseif((int)$user->getRoleId() === 4): ?>
                                        <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=2"> Utilisateur </a>
                                        | 
                                        <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId="> Éditeur </a>
                                    <?php else : ?>    
                                        <a href="#"> Ø </a>
                                    <?php endif ?>                                    
                                </td>
                                <td>
                                <?php if((int)$user->getRoleId() === 1): ?>
                                    <a href="#"> Ø </a>
                                <?php else : ?>    
                                    <a href="?route=banUser&userId=<?= htmlspecialchars($user->getId()) ?>">Bannir</a>
                                    | 
                                    <a href="?route=deleteUser&userId=<?= htmlspecialchars($user->getId()) ?>">Supprimer</a>
                                <?php endif ?> 
                                </td>
                            </tr>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

