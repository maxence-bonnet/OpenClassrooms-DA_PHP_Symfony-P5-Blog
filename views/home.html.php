<!-- Waiting for Twig -->
<?php $this->title = "Accueil"; ?>

<h1><?= $this->title ?></h1>

<p>En construction</p>

<section class="container-fluid bg-light">

    <?= $this->session->show('loginSuccess'); ?>
    <?= $this->session->show('banned'); ?>
    <?= $this->session->show('logout'); ?>

    <?= $this->session->show('messageSent'); ?>
    <?= $this->session->show('messageFailure'); ?>
    
    <?= $this->session->show('alreadyLoggedIn'); ?>
    <?= $this->session->show('adminOnly'); ?>
    


    <h1> Maxence Bonnet</h1>

    <!-- <img src="../public/uploads/photo.jpg" alt="Ceci est une photo"> -->

    <h2> Slogan </h2>

    <p> [...] </p>

    <p>
        <a class="nav-link" href="../public/uploads/CV.pdf" target="_blank">Mon CV</a>
    </p>

    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut temporibus nam, quod molestiae ea atque suscipit consequuntur tempora itaque. Quaerat nam facere alias praesentium eveniet ex non incidunt corporis explicabo?
    Error vitae asperiores provident accusantium blanditiis non quidem, aut impedit. Molestias cumque dolorum error voluptas eos similique vitae molestiae quaerat ullam nemo ratione nulla aliquam ipsa, nesciunt modi iusto officia.</p>

</section>

<section class="container mb-3">
    <div class="row justify-content-md-center">
        <div class="col-6 bg-light shadow-lg">
        <h4 class="text-center text-md-start">Formulaire de contact</h4>
        <form action="" method="post">
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
                <label class="form-label" for="mail">Votre email :</label>
                <input type="email" class="form-control <?= isset($errors['mail']) ? "is-invalid" : ''; ?>" id="mailContent" name="mail" placeholder="exemple@monmail.com" value="<?= isset($post) ? htmlspecialchars($post->get('mail')): ''; ?>">
                <?= isset($errors['mail']) ? $errors['mail'] : ''; ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="message">Votre message :</label>
                <textarea class="form-control <?= isset($errors['message']) ? "is-invalid" : ''; ?>" id="messageContent" name="message" aria-describedby="messageContentHelp" style="height: 120px" placeholder="..."><?= isset($post) ? htmlspecialchars($post->get('message')): ''; ?></textarea>
                <?= isset($errors['message']) ? $errors['message'] : ''; ?>
                <div id="messageContentHelp" class="form-text">500 caractères maximum : 0/500</div>
            </div>
            <div class="mb-3 d-flex">
                <input type="submit" class="btn btn-primary mx-auto" id="submit" name="submit" value="Envoyer">
            </div>        
        </form>
        </div>
    </div>
</section>