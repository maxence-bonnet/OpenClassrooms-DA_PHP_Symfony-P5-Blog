<!-- Waiting for Twig -->
<?php $this->title = "Connexion"; ?>

<div class="row justify-content-md-center">
    <div class="col-6">
    <h4 class="text-center text-md-start">Connexion</h4>
    <form action="#" method="post">
        <?= isset($errors['login']) ? $errors['login'] : ''; ?>
        <div class="mb-3">
            <label class="form-label" for="pseudo">Votre identifiant* :</label>
            <input type="text" class="form-control <?= isset($errors['pseudo']) ? "is-invalid" : ''; ?>" id="pseudoContent" name="pseudo" placeholder="Jeanjean" value="<?= isset($post) ? htmlspecialchars($post->get('pseudo')): ''; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Votre mot de passe* :</label>
            <input type="password" class="form-control <?= isset($errors['password']) ? "is-invalid" : ''; ?>" id="passwordContent" name="password" aria-describedby="passwordContentHelp" placeholder="********">
        </div>
        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Se connecter">
    </form>
    </div>
</div>