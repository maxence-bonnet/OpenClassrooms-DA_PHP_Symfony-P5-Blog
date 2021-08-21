<?php $this->title = "Accueil"; ?>
<h1><?= $this->title ?></h1>
<p>En construction</p>

<?php
if ($this->session->get('pseudo')) {
    ?>
    <a href="../public/index.php?route=logout">Déconnexion</a>
    <a href="../public/index.php?route=profile">Profil</a>
    <?php if($this->session->get('role') === 'admin') { ?>
        <a href="../public/index.php?route=administration">Administration</a>
    <?php } ?>
    
    <?php
} else {
    ?>
    <a href="../public/index.php?route=register">Inscription</a>
    <a href="../public/index.php?route=login">Connexion</a>
    <?php
}

foreach($articles as $article)
{
    $createdAt = new DateTime(htmlspecialchars($article->getCreatedAt()));
    $createdAt = $createdAt->format('d-m-y H:i');
    ?>
    <div>
        <h2><a href="../public/index.php?route=article&articleId=<?= htmlspecialchars($article->getId());?>"><?= htmlspecialchars($article->getTitle());?></a></h2>
        <p><?= nl2br(htmlspecialchars($article->getContent()));?></p>
        <p><?= htmlspecialchars($article->getAuthorPseudo());?></p>
        <p>Créé le : <?= $createdAt;?></p>
        <?php
        if ($article->getLastModified() !== $article->getCreatedAt()) {
            $lastModified = new DateTime(htmlspecialchars($article->getLastModified()));
            $lastModified = $lastModified->format('d-m-y H:i');
            ?>
            <p>Modifié le : <?= $lastModified;?></p>
            <?php
        }
        ?>
    </div>
    <br>
    <?php
}
?>