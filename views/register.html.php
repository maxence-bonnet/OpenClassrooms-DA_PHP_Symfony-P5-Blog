<!-- Waiting for Twig -->
<?php $this->title = "Inscription"; ?>

<div class="row justify-content-md-center">
    <div class="col-6">
    <h4 class="text-center text-md-start">Formulaire d'inscription</h4>
    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label" for="lastname">Votre nom* :</label>
            <input type="text" class="form-control <?= isset($errors['lastname']) ? "is-invalid" : ''; ?>" id="lastnameContent" name="lastname"  aria-describedby="lastnameContentHelp" placeholder="Dupont" value="<?= isset($post) ? htmlspecialchars($post->get('lastname')): ''; ?>">
            <?= isset($errors['lastname']) ? $errors['lastname'] : ''; ?>
            <div id="lastnameContentHelp" class="form-text">60 caractères maximum : 0/60</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="firstname">Votre prénom* :</label>
            <input type="text" class="form-control <?= isset($errors['firstname']) ? "is-invalid" : ''; ?>" id="firstnameContent" name="firstname" aria-describedby="firstnameContentHelp" placeholder="Jean" value="<?= isset($post) ? htmlspecialchars($post->get('firstname')): ''; ?>">
            <?= isset($errors['firstname']) ? $errors['firstname'] : ''; ?>
            <div id="firstContentHelp" class="form-text">60 caractères maximum : 0/60</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="pseudo">Votre identifiant / Pseudonyme* :</label>
            <input type="text" class="form-control <?= isset($errors['pseudo']) || isset($errors['pseudoExists']) ? "is-invalid" : ''; ?>" id="pseudoContent" name="pseudo" aria-describedby="pseudoContentHelp" placeholder="Jeanjean" value="<?= isset($post) ? htmlspecialchars($post->get('pseudo')): ''; ?>">
            <?= isset($errors['pseudoExists']) ? $errors['pseudoExists'] : ''; ?>
            <?= isset($errors['pseudo']) ? $errors['pseudo'] : ''; ?>
            <div id="pseudoContentHelp" class="form-text">Identifiant unique<br>60 caractères maximum : 0/60</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Votre mot de passe* : @Azerty1</label>
            <input type="password" class="form-control <?= isset($errors['password']) ? "is-invalid" : ''; ?>" id="passwordContent" name="password" aria-describedby="passwordContentHelp" placeholder="">
            <?= isset($errors['password']) ? $errors['password'] : ''; ?>
            <div id="passwordContentHelp" class="form-text">1 chiffre, 1 majuscule, 1 caratère spécial et 8 caractères minimum</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password2">Confirmer le mot de passe* :</label>
            <input type="password" class="form-control <?= isset($errors['password2']) ? "is-invalid" : ''; ?>" id="password2Content" name="password2" aria-describedby="password2ContentHelp" placeholder="">
            <?= isset($errors['password2']) ? $errors['password2'] : ''; ?>
            <div id="password2ContentHelp" class="form-text">Les mots de passe doivent être identiques</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="mail">Votre email* :</label>
            <input type="email" class="form-control <?= isset($errors['mail']) ? "is-invalid" : ''; ?>" id="mailContent" name="mail" aria-describedby="mailContentHelp" placeholder="exemple@monmail.com" value="<?= isset($post) ? htmlspecialchars($post->get('mail')): ''; ?>">
            <?= isset($errors['mail']) ? $errors['mail'] : ''; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="phone">Votre numéro de téléphone :</label>
            <input type="phone" class="form-control <?= isset($errors['phone']) ? "is-invalid" : ''; ?>" id="phoneContent" name="phone" aria-describedby="phoneContentHelp" placeholder="0612345678" value="<?= isset($post) ? htmlspecialchars($post->get('phone')): ''; ?>" maxlength="10">
            <?= isset($errors['phone']) ? $errors['phone'] : ''; ?>
            <div id="phoneContentHelp" class="form-text">format 06.12.34.56.78</div>
        </div>
        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Valider">
    </form>
    </div>
</div>