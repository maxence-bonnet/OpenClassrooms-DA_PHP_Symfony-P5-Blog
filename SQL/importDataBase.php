<?php

require dirname(__DIR__) . '/config/developpement.php';


echo "Création de la base de données en cours \n";

$query = file_get_contents(__DIR__ . '/blog_maxence.sql');

try {

    $pdo = new PDO(DB_HOST,DB_USER,DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec($query);

} catch (Exception $error) {

    throw new Exception('Erreur lors de la création : ' . $error->getMessage());

}

echo "Création terminée !";