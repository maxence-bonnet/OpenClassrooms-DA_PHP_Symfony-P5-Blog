<!-- Waiting for Twig -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <title><?= $title ?></title>
</head>
<body>
    <header>
        <div class="container-fluid">

        </div>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="container-fluid">
            <a class="navbar-brand" href="#">LOGO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="../public">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../public/index.php?route=articles">Les articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../public/index.php?route=addArticle">Nouvel article</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../public/uploads/CV.pdf" target="_blank">Mon CV</a>
                    </li>
                    <?php
                   // if ($this->session->get('pseudo')) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../public/index.php?route=logout">Déconnexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../public/index.php?route=profile">Profil</a>
                        </li>
                        <?php //if($this->session->get('role') === 'admin') { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../public/index.php?route=administration">Administration</a>
                            </li>
                        <?php //} ?>
                        
                        <?php
                    //} else {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../public/index.php?route=register">Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../public/index.php?route=login">Connexion</a>
                        </li>
                        <?php
                    //}
                    ?>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container" id="content">
        <?= $content ?>
    </div>
    <footer>
        <div class="container-fluid">
            <a href="#">Github</a>
            <a href="#">Linkedin</a>
            <a href="#">Twitter</a>
        </div>
    </footer>
    <script src="../public/js/bootstrap.min.js"></script>
</body>
</html>

