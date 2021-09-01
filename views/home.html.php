<!-- Waiting for Twig -->
<?php $this->title = "Accueil"; ?>

<h1><?= $this->title ?></h1>

<p>En construction</p>

<?= $this->session->show('Registered'); ?>

<h1> Maxence Bonnet</h1>

<!-- <img src="../public/uploads/photo.jpg" alt="Ceci est une photo"> -->

<h2> Ceci est un slogan </h2>

<p> blabla </p>

<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut temporibus nam, quod molestiae ea atque suscipit consequuntur tempora itaque. Quaerat nam facere alias praesentium eveniet ex non incidunt corporis explicabo?
Error vitae asperiores provident accusantium blanditiis non quidem, aut impedit. Molestias cumque dolorum error voluptas eos similique vitae molestiae quaerat ullam nemo ratione nulla aliquam ipsa, nesciunt modi iusto officia.</p>

<div class="row">
    <div class="col-6">
    <h4 class="text-center text-md-start">Formulaire de contact</h4>
    <form action="#" method="post">
        <div class="mb-3">
            <label class="form-label" for="firstname">Votre nom :</label>
            <input type="text" class="form-control <?= isset($errors['lastname']) ? "is-invalid" : ''; ?>" id="lastnameContent" name="lastname" placeholder="Dupont" value="<?= isset($post) ? htmlspecialchars($post->get('lastname')): ''; ?>">
            <?= isset($errors['lastname']) ? $errors['lastname'] : ''; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="firstname">Votre prénom :</label>
            <input type="text" class="form-control <?= isset($errors['firstname']) ? "is-invalid" : ''; ?>" id="firstnameContent" name="firstname" placeholder="Jean" value="<?= isset($post) ? htmlspecialchars($post->get('firstname')): ''; ?>">
            <?= isset($errors['firstname']) ? $errors['firstname'] : ''; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="firstname">Votre email :</label>
            <input type="mail" class="form-control <?= isset($errors['mail']) ? "is-invalid" : ''; ?>" id="mailContent" name="mail" aria-describedby="mailContentHelp" placeholder="exemple@monmail.com" value="<?= isset($post) ? htmlspecialchars($post->get('mail')): ''; ?>">
            <?= isset($errors['firstname']) ? $errors['firstname'] : ''; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="message">Votre message :</label>
            <textarea class="form-control <?= isset($errors['content']) ? "is-invalid" : ''; ?>" id="messageContent" name="message" aria-describedby="messageContentHelp" style="height: 120px" placeholder="..."><?= isset($post) ? htmlspecialchars($post->get('message')): ''; ?></textarea>
            <?= isset($errors['message']) ? $errors['message'] : ''; ?>
            <div id="messageContentHelp" class="form-text">...<br>500 caractères maximum : 0/500</div>
        </div>
        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Envoyer">
    </form>
    </div>
</div>