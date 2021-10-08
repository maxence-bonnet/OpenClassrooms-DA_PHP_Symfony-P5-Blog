# Projet 5 du parcours développeur d'application PHP / Symfony chez OpenClassrooms

## Contexte

Ce projet consiste en la réalisation d'un blog personnel PHP avec une une approche Orientée Objet.
Globalement le blog doit inclure :
   * une page d'accueil pour se présenter
   * un espace de connexion & inscription pour les visiteurs
   * une page listant les articles du blog
   * une page présentant un article 
   * une page permettant la rédaction d'un article
   * un espace d'administration permettant de :
        * modérer les commentaires
        * gérer les articles
        * gérer les utilisateurs

Les commentaires postés par les utilisateurs doivent être validés par l'administrateur / modérateur avant d'être rendu public

# Installation du blog

## Prérequis

 * PHP 7.2.5 +
 * MySQL 10 +
 * [Composer](https://getcomposer.org/)
 * [Bootstrap v5 +](https://getbootstrap.com/)

## 1] Cloner le projet

Une fois rendu dans le dossier de votre choix

### a] Via le terminal Git :
```shell
git clone https://github.com/maxence-bonnet/OpenClassrooms-DA_PHP_Symfony-P5-Blog.git
```
### a-bis] Ou en téléchargeant manuellement depuis le dépôt Github :

Code -> Download ZIP -> puis extraire dans votre dossier

#### Puis

- Renommer le dossier qui contient les fichiers par "P5_blog" 

#### Ou

- Dans le fichier .htaccess :

remplacer la ligne : `RewriteBase /P5_blog/`

par le nom de votre dossier : `RewriteBase /Nom_de_votre_dossier/`


## 2] Installation des dépendances via composer
```shell
php composer.phar update
```

## 3] Initialisation de la base de données

Mettre à jour le fichier `config/developpement.php` avec vos propres paramètres de connexion  :

```php
const HOST = 'localhost'; // adresse de l'hôte
const DB_NAME = ''; // Laisser vide pour le moment
const CHARSET = 'utf8';
const DB_USER = 'root'; // identifiant utilisateur
const DB_PASS = ''; // mot de passe utilisateur
```

### a]
Exécuter dans un terminal le script php à dispositon pour créer la base de données :

```
php .\SQL\importDataBase.php
```

### a-bis]
Ou importer le fichier blog.sql directement sur PhpMyAdmin si disponnible

Mettre à jour le fichier `config/developpement.php` :

```php
const DB_NAME = 'blog_maxence'; 
```
## 4] Bootstrap

Intégrer les fichiers Bootstrap 5 dans les dossiers css / js

`public/css/bootstrap`

`public/js/bootstrap`

## 5] Utilisation
Si tout s'est bien déroulé, le site devrait désormais être fonctionnel, rendez-vous à la racine du site.

Vous pouvez vous connecter avec le compte administrateur pour tester l'ensemble :

pseudo :  admin

mot de passe : @Azerty1

En plus de ce qui est demandé, il est possible de :

  * Filtrer les articles par mot clé ou par catégorie (page listant les articles)
  * Répondre à un commentaire (hiérarchie commentaire / réponse)
  * Un utilisateur peut modifier son propre commentaire ou le supprimer
  * Accéder à la page de profil d'un utilisateur (en développement)
  * Choisir un thème sur sa propre page de profil
 
L'espace d'adminitration permet de faire des recherches précises (commentaires / articles / utilisateurs) avec un système de filtre flexible.


## Codacy Review

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/79f37147ee3e460f876eb294ca8e4873)](https://www.codacy.com/gh/maxence-bonnet/OpenClassrooms-DA_PHP_Symfony-P5-Blog/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=maxence-bonnet/OpenClassrooms-DA_PHP_Symfony-P5-Blog&amp;utm_campaign=Badge_Grade)

## Pistes d'améliorations

  * Ajouter une fonctionnalité de réaction (like / dislike / ...)
  * Système de score utilisateur (fonction de l'activité / like)
  * Finir d'implémenter les rôles Éditeur / Modérateur
  * Gestion des catégories 
  * Ajouter un journal d'activité (administration & utilisateur)
  * Modifier et réninitialiser un mot de passe (utilisateur)
  * Photo de profil et description / biographie (utilisateur)
  * Revoir l'esthétique globale