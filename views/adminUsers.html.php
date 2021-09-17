<!-- Waiting for Twig -->
<?php $this->title = "Administration Utilisateurs"; ?>

<h1><?= $this->title ?></h1>

<p>En construction</p>

<?= $this->session->show('updatedUserRole'); ?>

<section class="container bg-light">
    <h2>Tous les Utilisateurs</h2>
    <div class="row">
        <div class="col-md-6">
            <form action="" method="get" class="mb-3">
                <input type="hidden" name="route" value="adminUsers">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="q">Pseudo : </label>
                    <input type="text" class="form-control" name="q" id="q" placeholder="..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']): '';?>">                        
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="scoreHigherThan"> Score supérieur à : </label>
                    <input type="number" class="form-control" name="scoreHigherThan" id="scoreHigherThan" value="<?= isset($_GET['scoreHigherThan']) ? (int)$_GET['scoreHigherThan'] : ''?>">
                    <label class="input-group-text ms-2" for="scoreLowerThan"> Score inférieur à :</label>
                    <input type="number" class="form-control" name="scoreLowerThan" id="scoreLowerThan" value="<?= isset($_GET['scoreLowerThan']) ? (int)$_GET['scoreLowerThan'] : ''?>">                     
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="afterDatetime"> Inscrit Après : </label>
                    <input type="datetime-local" class="form-control" name="afterDatetime" id="afterDatetime" value="<?= isset($_GET['afterDatetime']) ? htmlspecialchars($_GET['afterDatetime']) : ''?>">
                    <label class="input-group-text ms-2" for="beforeDatetime"> Inscrit Avant : </label>
                    <input type="datetime-local" class="form-control" name="beforeDatetime" id="beforeDatetime" value="<?= isset($_GET['beforeDatetime']) ? htmlspecialchars($_GET['beforeDatetime']) : ''?>">                     
                </div>
                <h4>Avec le statut : </h4>
                <div class="input-group mb-3">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="online" id="online" <?= isset($_GET['online']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="online"> En ligne </label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="offline" id="offline" <?= isset($_GET['offline']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="offline"> Hors ligne </label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="banned" id="banned" <?= isset($_GET['banned']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="banned"> Bannis </label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="allUserStatus" id="allUserStatus" <?= isset($_GET['allUserStatus']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="allUserStatus"> Tous </label>
                    </div>                        
                </div>
                <h4> Role : </h4>
                <div class="input-group mb-3">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="admin" id="admin" <?= isset($_GET['admin']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="admin"> Administrateur </label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="moderator" id="moderator" <?= isset($_GET['moderator']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="moderator"> Modérateur </label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="editor" id="editor" <?= isset($_GET['editor']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="editor"> Éditeur </label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="user" id="user" <?= isset($_GET['user']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="user"> Utilisateur </label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="allUserRoles" id="allUserRoles" <?= isset($_GET['allUserRoles']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="allUserRoles"> Tous </label>
                    </div>                        
                </div>               
                <div class="input-group mb-3">
                    <label class="input-group-text" for="limit">Nombre de réulstats par page</label>
                    <select  class="form-select me-auto" name="limit" id="limit">
                        <option value="10" <?= isset($_GET['limit']) && $_GET['limit'] === "10" ?'selected':'';?>>10</option>
                        <option value="20" <?= !isset($_GET['limit']) || $_GET['limit'] === "20" ?'selected':'';?>>20</option>
                        <option value="30" <?= isset($_GET['limit']) && $_GET['limit'] === "30" ?'selected':'';?>>30</option>
                        <option value="40" <?= isset($_GET['limit']) && $_GET['limit'] === "40" ?'selected':'';?>>40</option>
                        <option value="50" <?= isset($_GET['limit']) && $_GET['limit'] === "50" ?'selected':'';?>>50</option>
                    </select>     
                </div>
                <button class="btn btn-primary">Rechercher</button>
            </form>         
        </div>
    </div>
    <div class="row">
        <div class="col">
        <h5><?= $pageUsersCount ?> / <?= $totalUsersCount ?> Résultat(s) - Page <?= $page ?> / <?= $pages > 0 ? $pages : '1' ?></h5>
            <table class="table table-striped table-hover table-dark">
            <thead>
                    <tr>                    
                        <td> Pseudo </td>
                        <td> Date d'inscription</td>
                        <td> Role </td>
                        <td> Score </td>
                        <td> Statut </td>
                        <td> Modifier le role </td>
                        <td> Bannir / Supprimer </td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user) : ?>
                        <tr>
                            <td>
                                <a href="?route=profile&userId=<?= htmlspecialchars($user->getId()) ?>"><?= htmlspecialchars($user->getPseudo()) ?></a>
                            </td>
                            <td>
                                <?= $user->getFormatedDate($user->getCreatedAt()) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($user->getRoleName()) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($user->getScore()) ?>
                            </td>
                            <td>
                                <?= $user->getStatusName(); ?>
                            </td>
                            <td>
                                <?php if((int)$user->getRoleId() === 2): ?>
                                    <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=3"> Éditeur </a>
                                    | 
                                    <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=4"> Modérateur </a>
                                <?php elseif((int)$user->getRoleId() === 3): ?>
                                    <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=2"> Utilisateur </a>
                                    | 
                                    <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=4"> Modérateur </a>
                                <?php elseif((int)$user->getRoleId() === 4): ?>
                                    <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId=2"> Utilisateur </a>
                                    | 
                                    <a href="?route=updateUserRole&userId=<?= htmlspecialchars($user->getId()) ?>&roleId="> Éditeur </a>
                                <?php else : ?>    
                                    <a href="#"> Ø </a>
                                <?php endif ?>  
                            </td>
                            <td>
                                <?php if((int)$user->getRoleId() === 1): ?>
                                    <a href="#"> Ø </a>
                                <?php else : ?>    
                                    <a href="?route=banUser&userId=<?= htmlspecialchars($user->getId()) ?>">Bannir</a>
                                    | 
                                    <a href="?route=deleteUser&userId=<?= htmlspecialchars($user->getId()) ?>">Supprimer</a>
                                <?php endif ?> 
                            </td>
                        </tr>
                    <?php endforeach ?>

                    <?php if(empty($users)) : ?>
                        <td colspan="7" class="text-center">
                            Aucun utilisateur correspondant à la recherche.
                        </td>
                    <?php endif ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between my-4">
                <?php
                if($previousPageUrl){
                    ?>
                    <a href="?<?= $previousPageUrl ?>" class="btn btn-primary"> Page précédente </a>
                    <?php
                }

                if($nextPageUrl){
                    ?>
                    <a href="?<?= $nextPageUrl ?>" class="btn btn-primary ms-auto"> Page suivante </a>
                    <?php                
                }
                ?>
            </div>
        </div>    
</section>