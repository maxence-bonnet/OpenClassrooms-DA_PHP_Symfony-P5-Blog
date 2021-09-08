<!-- Waiting for Twig -->
<?php $this->title = "Administration"; ?>

<h1><?= $this->title ?></h1>

<p>En construction</p>

<?= $this->session->show('deletedComment'); ?>
<?= $this->session->show('validatedComment'); ?>
<?= $this->session->show('updatedArticle'); ?>
<?= $this->session->show('deletedArticle'); ?>

<section class="container bg-light">
    <h2>Commentaires en attente de validation</h2>
    <div class="row">
        <div class="col">
            <table class="table table-striped table-hover table-dark">
                <thead>
                    <tr>                    
                        <td> Auteur </td>
                        <td> Extrait </td>
                        <td> Titre de l'article </td>
                        <td> Valider le commentaire </td>
                        <td> Supprimer le commentaire </td>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($comments as $comment){
                        $createdAt = new DateTime(htmlspecialchars($comment->getCreatedAt()));
                        $createdAt = $createdAt->format('d-m-y H:i');
                        ?>
                        <tr>
                            <td>
                                <a href="../public/index.php?route=profile&userId=<?= htmlspecialchars($comment->getUserId()) ?>"><?= htmlspecialchars($comment->getUserPseudo()) ?></a>
                            </td>
                            <td>
                                <?= htmlspecialchars($comment->getcontent()) ?>
                            </td>
                            <td>
                                <a href="../public/index.php?route=article&articleId=<?= htmlspecialchars($comment->getArticleId()) ?>"><?= htmlspecialchars($comment->getArticleTitle()) ?> </a> 
                            </td>
                            <td>
                                <a href="../public/index.php?route=validateComment&commentId=<?= htmlspecialchars($comment->getId()) ?>"> Valider </a> 
                            </td>
                            <td>
                                <a href="../public/index.php?route=deleteComment&commentId=<?= htmlspecialchars($comment->getId()) ?>"> Supprimer </a>
                            </td>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>    
</section>

<section class="container bg-light">
    <h2>Les articles</h2>
    <div class="row">
        <div class="col">
            <table class="table table-striped table-hover table-dark">
                <thead>
                    <tr>                    
                        <td> Titre </td>
                        <td> Date de création </td>
                        <td> Modifier L'article </td>
                        <td> Changer le statut </td>
                        <td> Supprimer </td>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($articles as $article){
                        $createdAt = new DateTime(htmlspecialchars($article->getCreatedAt()));
                        $createdAt = $createdAt->format('d-m-y H:i');
                        ?>
                        <tr>
                            <td>
                                <a href="../public/index.php?route=article&articleId=<?= htmlspecialchars($article->getId()) ?>"><?= htmlspecialchars($article->getTitle()) ?></a>
                            </td>
                            <td>
                                <?= $createdAt ?>
                            </td>
                            <td>
                                <a href="../public/index.php?route=editArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Modifier</a> 
                            </td>
                            <td>
                                <a href="../public/index.php?route=editArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Publier | </a> 
                                <a href="../public/index.php?route=editArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Publier en privé | </a> 
                                <a href="../public/index.php?route=editArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Retirer des publications</a> 
                            </td>
                            <td>
                                <a href="../public/index.php?route=deleteArticle&articleId=<?= htmlspecialchars($article->getId()) ?>">Supprimer</a>
                            </td>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="container bg-light">
    <h2>Les utilisateurs</h2>
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
                        $createdAt = new DateTime(htmlspecialchars($user->getCreatedAt()));
                        $createdAt = $createdAt->format('d-m-y H:i');
                        ?>
                        <tr>
                            <td>
                                <a href="../public/index.php?route=profile&userId=<?= htmlspecialchars($user->getId()) ?>"><?= htmlspecialchars($user->getPseudo()) ?></a>
                            </td>
                            <td>
                                <?= $createdAt ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($user->getRole()) === "user" ? 'Utilisateur' : "Administrateur" ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($user->getScore()) ?>
                            </td>
                            <td>
                                <?= $user->getStatus() > 0 ? 'En ligne' : 'Déconnecté' ?>
                            </td>
                            <td>
                                <a href="#">*changer le role*</a> 
                            </td>
                            <td>
                                <a href="../public/index.php?route=deleteUser&userId=<?= htmlspecialchars($user->getId()) ?>">Supprimer</a>
                            </td>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

