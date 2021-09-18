<?php $this->title ?>

<h1><?= $this->title ?></h1>

<div class="col-6">
    <h4 class="text-center text-md-start">Modifier le commentaire</h4>
    <form method="post" action="?route=adminEditComment&commentId=<?= htmlspecialchars($comment->getId()) ?>">
        <div class="row">
            <div class="col-2">
            </div>
            <div class="col">
                <div class="mb-3">
                    <textarea class="form-control <?= isset($errors['content']) ? "is-invalid" : ''; ?>" id="commentContent" name="content" aria-describedby="commentContentHelp" style="height: 120px" placeholder="C'est énorme j'adore !"><?= isset($post) ? htmlspecialchars($post->get('content')) : ''; ?></textarea>
                    <?= isset($errors['content']) ? $errors['content'] : ''; ?>
                    <div id="commentContentHelp" class="form-text">500 caractères maximum : 0/500</div>
                </div>
                <input type="hidden" name="answer_to" value="">
                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Valider">
            </div>
        </div>
    </form>                       
</div>