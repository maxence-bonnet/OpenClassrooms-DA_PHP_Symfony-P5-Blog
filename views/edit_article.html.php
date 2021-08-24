<?php

if(isset($post) && $post->get('id')) {
    $new = false;
    $statusId = htmlspecialchars($post->get('statusId'));
    $editTitle = "Modification de l'article : " . htmlspecialchars($post->get('title'));
    $this->title = "Modif :" . htmlspecialchars($post->get('title'));
    $route = 'editArticle&articleId=' . $post->get('id');
    $submit = 'Mettre à jour';
} else {
    $new = true;
    $editTitle = "Création d'un nouvel article";
    $this->title = "Nouvel article";
    $route = 'addArticle';
    $submit = 'Créer l\'article';
}
?>

<div class="row justify-content-md-center">
    <div class="col-9">
        <h1 class=""><?= $editTitle ?></h1>
        <form method="post" action="../public/index.php?route=<?= $route; ?>">
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label" for="title">Titre de l'article</label>
                        <input type="text" class="form-control <?= isset($errors['title']) ? "is-invalid" : ''; ?>" id="titleContent" name="title" aria-describedby="titleContentHelp" value="<?= isset($post) ? htmlspecialchars($post->get('title')): ''; ?>">
                        <div id="titleContentHelp" class="form-text">63 caractères maximum : 0/63</div>
                        <?= isset($errors['title']) ? $errors['title'] : ''; ?>
                    </div>
                    <div class="mb-3">
                        <!-- C'est débile de pouvoir changer le nom de l'auteur s'il n'y a qu'un seul admin (...) -->
                        <label class="form-label" for="authorPseudo">Nom de l'auteur :</label>
                        <input type="text" class="form-control <?= isset($errors['authorPseudo']) ? "is-invalid" : ''; ?>" id="authorPseudo" name="authorPseudo"value="<?= isset($post) ? htmlspecialchars($post->get('authorPseudo')): ''; ?>"  disabled>
                        <input type="hidden" class="form-control" id="authorId" name="authorId"value="<?= isset($post) ? htmlspecialchars($post->get('authorId')): '1'; ?>">
                        <?= isset($errors['authorPseudo']) ? $errors['authorPseudo'] : ''; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="category">Catégorie de l'article : </label>
                        <input type="text" class="form-control <?= isset($errors['category']) ? "is-invalid" : ''; ?>" id="category" name="category"value="<?= isset($post) ? htmlspecialchars($post->get('category')): ''; ?>"  disabled>
                        <?= isset($errors['category']) ? $errors['category'] : ''; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="lede">Chapô :</label>
                        <textarea class="form-control <?= isset($errors['lede']) ? "is-invalid" : ''; ?>" id="ledeContent" name="lede" aria-describedby="ledeContentHelp" style="height: 120px"><?= isset($post) ? htmlspecialchars($post->get('lede')): ''; ?></textarea>
                        <div id="ledeContentHelp" class="form-text">500 caractères maximum : 0/500</div>
                        <?= isset($errors['lede']) ? $errors['lede'] : ''; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="content">Contenu de l'article :</label>
                        <textarea class="form-control <?= isset($errors['content']) ? "is-invalid" : ''; ?>" id="contentContent" name="content" aria-describedby="contentContentHelp" style="height: 500px"><?= isset($post) ? htmlspecialchars($post->get('content')): ''; ?></textarea>
                        <div id="contentContentHelp" class="form-text">2000 caractères maximum : 0/2000</div>
                        <?= isset($errors['content']) ? $errors['content'] : ''; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="allowComment">Autoriser les commentaires sur cet article</label>
                        <input type="checkbox" class="form-check-input" id="allowComment" name="allowComment" value="1" checked>
                    </div>
                    <div class="mb-3">
                        <div class="form-check-inline">
                            <input class="form-check-input" type="radio" name="statusId" id="statusId1" value="1" checked>
                            <label class="form-check-label" for="statusId1"> Publier  </label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="radio" name="statusId" id="statusId2" value="2">
                            <label class="form-check-label" for="statusId2"> Publier en privé </label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="radio" name="statusId" id="statusId3" value="3">
                            <label class="form-check-label" for="statusId3"> Attendre avant publication </label>
                        </div>                        
                    </div>
                    <input type="submit" class="btn btn-primary" id="submit" name="submit" value="<?= $submit; ?>">
                </div>
            </div>
        </form>                       
    </div>      
</div>
