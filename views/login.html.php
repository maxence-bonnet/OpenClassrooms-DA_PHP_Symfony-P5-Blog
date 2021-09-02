<!-- Waiting for Twig -->
<?php $this->title = "Connexion"; ?>

<div class="row justify-content-md-center">
    <div class="col-6">
    <h4 class="text-center text-md-start">Connexion</h4>

    <?= $this->session->show('Registered'); ?>
    <?= $this->session->show('LoginNeeded'); ?>
    <?= $this->session->show('LoginError'); ?>
    
    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label" for="pseudo">Votre identifiant / Pseudonyme* :</label>
            <input type="text" class="form-control" id="pseudoContent" name="pseudo" placeholder="Jeanjean" value="<?= isset($post) ? htmlspecialchars($post->get('pseudo')): ''; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Votre mot de passe* :</label>
            <input type="password" class="form-control" id="passwordContent" name="password" aria-describedby="passwordContentHelp" placeholder="">
        </div>
        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Se connecter">
    </form>
    </div>
</div>