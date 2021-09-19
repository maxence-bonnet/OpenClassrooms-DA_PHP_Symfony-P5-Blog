<!-- Waiting for Twig -->
<!DOCTYPE html>
<html lang="fr" class="h-100">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="../public/css/bootstrap.min.css">
        <link rel="stylesheet" href="../public/css/style.css">
        <title><?= $title ?? ''?></title>
    </head>
    <body class="d-flex flex-column h-100">
        <header>
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <div class="container-fluid">
                <a class="navbar-brand" href="#">LOGO</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mb-2 mb-lg-0 w-100 px-2">
                        <li class="nav-item">
                            <a class="nav-link" href="../public">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?route=articles">Les articles</a>
                        </li>
                        <?php
                    if ($this->session->get('pseudo')) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="?route=profile">Profil</a>
                            </li>
                            <?php if($this->session->get('role') === 'admin') { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="?route=addArticle">Nouvel article</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="?route=administration">Administration</a>
                                </li>
                            <?php
                            }
                            ?>
                            <li class="nav-item ms-auto">
                                <a class="nav-link" href="?route=profile"><?= $this->session->get('pseudo') ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?route=logout">Déconnexion</a>
                            </li>
                            <?php
                        } else {
                            ?>
                            <li class="nav-item ms-auto">
                                <a class="nav-link" href="?route=register">Inscription</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?route=login">Connexion</a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="container" id="content">
            <?= $content ?>
        </div>
        <footer class="bg-light py-4 footer mt-auto">
            <div class="container d-flex">
                <a href="#" class="p-2">Github</a>
                <a href="#" class="p-2">Linkedin</a>
                <a href="#" class="p-2">Twitter</a>
                <?php if($this->session->get('role') === 'admin') : ?>
                    <a href="?route=administration" class="p-2"> Administration </a>
                    <span class="d-inline-block ms-auto p-2"> Généré en <?=  round(1000*(microtime(true)-PAGE_GENERATING_TIME)) ?> ms </span>
                <?php endif ?>
            </div>
        </footer>
        <script src="../public/js/bootstrap.min.js"></script>
    </body>
</html>

