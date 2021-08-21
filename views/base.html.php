<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title><?= $title ?></title>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="../public">Accueil</a></li>
            <li><a href="../public/index.php?route=articles">Les articles</a></li>
            <?php
            if ($this->session->get('pseudo')) {
                ?>
                <li><a href="../public/index.php?route=logout">Déconnexion</a></li>
                <li><a href="../public/index.php?route=profile">Profil</a></li>
                <?php if($this->session->get('role') === 'admin') { ?>
                    <li><a href="../public/index.php?route=administration">Administration</a></li>
                <?php } ?>
                
                <?php
            } else {
                ?>
                <li><a href="../public/index.php?route=register">Inscription</a></li>
                <li><a href="../public/index.php?route=login">Connexion</a></li>
                <?php
            }
            ?>
        </ul>
    </nav>

    <div id="content">
        <?= $content ?>
    </div>
</body>
</html>

