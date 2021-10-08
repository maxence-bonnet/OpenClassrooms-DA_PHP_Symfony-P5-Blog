-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 08 oct. 2021 à 13:03
-- Version du serveur :  10.4.18-MariaDB
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog_maxence`
--
CREATE DATABASE IF NOT EXISTS `blog_maxence` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `blog_maxence`;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(6) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `title` varchar(63) NOT NULL,
  `lede` text NOT NULL COMMENT 'introduction / teaser',
  `content` text NOT NULL,
  `author_id` int(6) DEFAULT NULL,
  `category_id` int(3) DEFAULT NULL,
  `status_id` int(1) NOT NULL COMMENT '1 : published\r\n2 : private\r\n3 : hidden',
  `allow_comment` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `created_at`, `last_modified`, `title`, `lede`, `content`, `author_id`, `category_id`, `status_id`, `allow_comment`) VALUES
(1, '2021-10-08 12:00:49', NULL, 'Stratagème I L’extension', 'Il s’agit de reprendre la thèse adverse en l’élargissant hors de ses limites naturelles, en lui donnant un sens aussi général et large que possible et l’exagérer, tout en maintenant les limites de ses propres positions aussi restreintes que possible. Car plus une thèse est générale et plus il est facile de lui porter des attaques. Se défendre de cette stratégie consiste à formuler une proposition précise sur le *puncti* ou le *status controversiæ*.', ' Exemple 1  Je dis : « Les Anglais sont la première nation en ce qui concerne la dramaturgie. » Mon adversaire tenta alors de donner une *instance* du contraire et répondit : « Il est bien connu que les Anglais ne sont pas doués en musique, et donc en opéra. » Je réfutai l’attaque en lui rafraîchissant la mémoire : « La musique ne fait pas partie de la dramaturgie qui ne comprend que tragédie et comédie. » Mon adversaire le savait probablement mais avait tenté de généraliser mon propos afin d’y inclure toutes les représentations théâtrales, et donc l’opéra, et donc la musique, afin de me prendre en erreur sur ma thèse. Inversement, il est possible de défendre ses positions en réduisant davantage les limites dans lesquelles elles s’appliquent initialement, pour peu que notre formulation nous y aide.\n\n  Exemple 2 A dit : « La Paix de 1814 a donné à toutes les villes allemandes de la ligue hanséatique leur indépendance. » et B donne une *instantia in contrarium* en rappelant que Dantzig avait reçu son indépendance de Bonaparte et l’avait perdue par cette Paix. A se sauve : « J’ai dit toutes les villes allemandes de la ligue hanséatique : Dantzig était une ville polonaise. » Ce stratagème a déjà été mentionné par Aristote dans *Topiques*, VIII, 12, 11.\n\n  Exemple 3 Lamark, dans sa *Philosophie zoologique* rejette l’idée que les polypes puissent éprouver des sensations car ils sont dépourvus de nerfs. Il est cependant certain qu’il existe chez eux un sens de la perception : ceux-ci s’orientent en direction de la lumière en se déplaçant de tentacule en tentacule et peuvent saisir leurs proies. Il a donc été émis l’hypothèse que leur système nerveux s’étendait à travers tout leur corps en égale mesure, comme s’ils étaient fusionnés avec, car il est évident que les polypes possèdent la faculté de perception sans présenter d’organes sensoriels. Comme cette théorie réfute l’argument de Lamark, celui-ci a recours à la dialectique : « Dans ce cas toutes les parties du corps des polypes doivent être capable de toute sorte de perception, de mouvement et de pensée. Le polype aurait alors en chaque point de son corps tous les organes du plus parfait des animaux : chaque point pourrait voir, sentir, goûter, écouter, etc. Ou mieux : penser, juger, faire des conclusions : chaque particule de son corps serait un animal parfait, et le polype serait au-dessus de l’homme car chaque particule de son corps aurait toutes les capacités des hommes. En outre, il n’y aurait pas de raisons de ne pas étendre ce qui est vrai pour le polype à tous les monades, puis aux plantes, lesquelles sont elles aussi vivantes, etc. » En faisant usage de cette stratégie de dialectique, un écrivain trahit le fait qu’il sait avoir tort. Parce que quelqu’un a dit : « Tout leur corps perçoit la lumière et agit donc comme un nerf », Lamark a étendu au fait que le corps était capable de penser.', 1, 10, 1, 1),
(2, '2021-10-08 12:01:52', NULL, 'Stratagème II L’homonymie', 'Ce stratagème consiste à étendre une proposition à quelque chose qui a peu ou rien à voir avec le discours original hormis la similarité des termes employés afin de la réfuter triomphalement et donner l’impression d’avoir réfuté la proposition originale.', 'Ce stratagème consiste à étendre une proposition à quelque chose qui a peu ou rien à voir avec le discours original hormis la similarité des termes employés afin de la réfuter triomphalement et donner l’impression d’avoir réfuté la proposition originale.\n\n*Nota* : des mots sont *synonymes* lorsqu’ils représentent la même conception tandis que des *homonymes* sont deux conceptions couvertes par le même mot. Voir Aristote, *Topiques*, I, 13. « Profond », « coupant », « haut » pour parler tantôt de corps tantôt de ton sont des *homonymes* tandis que « honorable » et « honnête » sont des *synonymes*.\n\nOn peut voir ce stratagème comme étant identique au sophisme *ex homonymia*. Cependant, si le sophisme est évident, il ne trompera personne.\n\n    *Omne lumen potest extingui* *Intellectus est lumen* *Intellectus potest extingui*   Nous pouvons voir ici quatre termes : « lumière », utilisé à la fois au sens propre et au sens figuré. Mais dans les cas subtils ces homonymes couvrant plusieurs concepts peuvent induire en erreur.\n\n  Exemple 1 A : « Vous n’êtes pas encore initié aux mystères de la philosophie de Kant. » B : « Ah, mais s’il s’agit de mystères, cela ne m’intéresse pas ! »   Exemple 2 J’ai condamné le principe d’« honneur » comme étant ridicule car si un homme perd son honneur en recevant une insulte, il ne peut la laver qu’en rétorquant une insulte encore plus grande ou en faisant couler le sang de son adversaire ou le sien. J’ai soutenu que le véritable honneur d’un homme ne pouvait être terni par ce dont il souffre, mais uniquement par ses actions car il est impossible de prévoir ce qui peut nous arriver. Mon adversaire attaqua immédiatement mon argument en me prouvant triomphalement que lorsqu’un commerçant se faisait faussement accuser de malhonnêteté ou de mal tenir son affaire, c’était une attaque à son honneur et ce dernier souffrait à cause de ce qu’il subissait et ne pouvait être lavé qu’en punissant son agresseur et en le forçant à se rétracter.   Ici, l’homonyme imposé est celui entre l’*honneur civique*, également appelé *bon nom*, qui peut souffrir de la diffamation et du scandale, et l’*honneur chevaleresque* ou *point d’honneur*, qui peut souffrir de l’insulte. On ne peut ne pas tenir compte d’une attaque sur le premier qui doit être réfutée en public, et donc avec la même justification, une attaque sur le second ne peut pas non plus être ignoré mais ne peut être lavé que par le duel ou une insulte encore plus grande. Nous avons là une confusion entre deux choses complètement différentes qui se rassemblent dans l’homonyme *honneur* d’où provient l’altération du débat.', 1, 10, 1, 1),
(3, '2021-10-08 12:02:26', NULL, 'Stratagème III La généralisation des arguments adverses', 'Il s’agit de prendre une proposition κατα τι, *relative*, et de la poser comme απλως, *absolue* ou du moins la prendre dans un contexte complètement différent et puis la réfuter. L’exemple d’Aristote est le suivant : le Maure est noir, mais ses dents sont blanches, il est donc noir et blanc en même temps. Il s’agit d’un exemple inventé dont le sophisme ne trompera personne. Il faut donc prendre un exemple réel.', 'Exemple 1 Lors d’une discussion concernant la philosophie, j’ai admis que mon système soutenait les Quiétistes et les louait. Peu après, la conversation dévia sur Hegel et j’ai maintenu que ses écrits étaient pour la plupart ridicules, ou du moins, qu’il y avait de nombreux passages où l’auteur écrivait des mots en laissant au lecteur le soin de deviner leur signification. Mon adversaire ne tenta pas de réfuter cette affirmation *ad rem*, mais se contenta de l’*argumentum ad hominem* en me disant que je faisais la louange des Quiétistes alors que ceux-ci avaient également écrit de nombreuses bêtises. J’ai admis ce fait, mais pour le reprendre, j’ai dit que ce n’était pas en tant que philosophes et écrivains que je louais les Quiétistes, c’est-à-dire de leurs réalisations dans le domaine de la *théorie*, mais en tant qu’hommes et pour leur conduite dans le domaine *pratique*, alors que dans le cas d’Hegel, nous parlions de ses théories. Ainsi ai-je paré l’attaque. Les trois premiers stratagèmes sont apparentés : ils ont en commun le fait que l’on attaque quelque chose de différent que ce qui a été affirmé. Ce serait un *ignoratio elenchi* de se faire battre de telle façon. Dans tous les exemples que j’ai donnés, ce que dit l’adversaire est vrai et il se tient c’est en opposition apparente et non réelle avec la thèse. Tout ce que nous avons à faire pour parer ce genre d’attaque est de nier la validité du syllogisme, c’est-à-dire la conclusion qu’il tire, parce qu’il est en tort et nous sommes dans le vrai. Il s’agit donc d’une réfutation directe de la réfutation *per negationem consequentiæ*.\n\nIl ne faut pas admettre les véritables prémisses car on peut alors deviner les conclusions. Il existe cependant deux façons de s’opposer à cette stratégie que nous verrons dans les sections 4 et 5.', 1, 10, 1, 1),
(4, '2021-10-08 12:03:00', NULL, 'Stratagème IV Cacher son jeu', 'Lorsque l’on désire tirer une conclusion, il ne faut pas que l’adversaire voie où l’on veut en venir, mais quand même lui faire admettre les prémisses une par une, l’air de rien, sans quoi l’adversaire tentera de s’y opposer par toutes sortes de chicanes.', 'Lorsque l’on désire tirer une conclusion, il ne faut pas que l’adversaire voie où l’on veut en venir, mais quand même lui faire admettre les prémisses une par une, l’air de rien, sans quoi l’adversaire tentera de s’y opposer par toutes sortes de chicanes. S’il est douteux que l’adversaire admette les prémisses, il faut établir des prémisses à ces prémisses, faire des pré-syllogismes et s’arranger pour les faire admettre, peu importe l’ordre. Vous cachez ainsi votre jeu jusqu’à ce que votre adversaire ait approuvé tout ce dont vous aviez besoin pour l’attaquer. Ces règles sont données dans Aristote, *Topiques*, VIII, 1.\n\nCe stratagème n’a pas besoin d’être illustré par un exemple.', 1, 10, 1, 1),
(5, '2021-10-08 12:03:23', NULL, 'Stratagème V Faux arguments', 'On peut, pour prouver une assertion dans le cas où l’adversaire refuse d’approuver de vrais arguments, soit parce qu’il n’en perçoit pas la véracité, soit parce qu’il devine où l’on veut en venir, utiliser des arguments que l’on sait être faux.', 'On peut, pour prouver une assertion dans le cas où l’adversaire refuse d’approuver de vrais arguments, soit parce qu’il n’en perçoit pas la véracité, soit parce qu’il devine où l’on veut en venir, utiliser des arguments que l’on sait être faux. Dans ce cas, il faut prendre des arguments faux en eux-mêmes, mais vrais *ad hominem*, et argumenter avec la façon de penser de l’adversaire, c’est-à-dire *ex concessis*. Une conclusion vraie peut en effet découler de fausses prémisses, mais pas l’inverse. De même, on peut détourner les faux arguments de l’adversaire par de faux arguments qu’il pense être vrais. Il faut utiliser son mode de pensée contre lui. Ainsi, s’il est membre d’une secte à laquelle nous n’appartenons pas, nous pouvons utiliser la doctrine de secte contre lui. Aristote, *Topiques*, VIII, 9.', 1, 10, 1, 1),
(6, '2021-10-08 12:03:57', NULL, 'Stratagème VI Postuler ce qui n’a pas été prouvé', 'On fait une *petitio principii* en postulant ce qui n’a pas été prouvé', 'On fait une *petitio principii* en postulant ce qui n’a pas été prouvé, soit :\n\n1. en utilisant un autre nom, par exemple « bonne réputation » au lieu de « honneur », « vertu » au lieu de « virginité », etc. ou en utilisant des mots intervertibles comme « animaux à sang rouge » au lieu de « vertébrés » ;\n2. en faisant une affirmation générale couvrant ce dont il est question dans le débat : par exemple maintenir l’incertitude de la médecine en postulant l’incertitude de toute la connaissance humaine ;\n3. ou vice-versa, si deux choses découlent l’une de l’autre, et que l’une reste à prouver, on peut postuler l’autre ;\n4. si une proposition générale reste à prouver, on peut amener l’adversaire à admettre chaque point particulier. Ceci est l’inverse du deuxième cas.\n \nAristote, *Topiques*, VIII, 11.\n\nLe dernier chapitre des *Topiques* contient de bonnes règles pour s’entraîner à la dialectique.', 1, 10, 1, 1),
(7, '2021-10-08 12:04:29', NULL, 'Stratagème VII Atteindre le consensus par des questions', 'Si le débat est conduit de façon relativement stricte et formelle, et qu’il y a le désir d’arriver à un consensus clair, celui qui formule une proposition et veut la prouver peut s’opposer à son adversaire en posant des questions.', 'Si le débat est conduit de façon relativement stricte et formelle, et qu’il y a le désir d’arriver à un consensus clair, celui qui formule une proposition et veut la prouver peut s’opposer à son adversaire en posant des questions, afin de démontrer la vérité par ses admissions. Cette méthode érothématique (également appelée Socratique) était particulièrement en usage chez les Anciens, et quelques stratagèmes développés plus loin y sont associés (*ceux-ci dérivent librement des* Réfutations Sophistiques *d’Aristote, chapitre 15*).\n\nL’idée est de poser beaucoup de questions à large portée en même temps, comme pour cacher ce que l’on désire faire admettre. On soumet ensuite rapidement l’argument découlant de ces admissions : ceux qui ne sont pas vif d’esprit ne pourront pas suivre avec précision le débat et ne remarqueront pas les erreurs ou oublis de la démonstration.', 1, 10, 2, 1),
(8, '2021-10-08 12:04:48', NULL, 'Stratagème VIII Fâcher l’adversaire', 'Provoquez la colère de votre adversaire : la colère voile le jugement et il perdra de vue où sont ses intérêts. Il est possible de provoquer la colère de l’adversaire en étant injuste envers lui à plusieurs reprises, ou par des chicanes, et en étant généralement insolent.', 'Provoquez la colère de votre adversaire : la colère voile le jugement et il perdra de vue où sont ses intérêts. Il est possible de provoquer la colère de l’adversaire en étant injuste envers lui à plusieurs reprises, ou par des chicanes, et en étant généralement insolent.', 1, 10, 2, 1),
(9, '2021-10-08 12:05:02', NULL, 'Stratagème IX Poser les questions dans un autre ordre', 'Posez vos questions dans un ordre différent de celui duquel la conclusion dépend, et transposez-les de façon à ce que l’adversaire ne devine pas votre objectif. Il ne pourra alors pas prendre de précautions et vous pourrez utiliser ses réponses pour arriver à des conclusions différentes, voire opposées. Ceci est apparenté au stratagème 4 : cacher son jeu.', 'Posez vos questions dans un ordre différent de celui duquel la conclusion dépend, et transposez-les de façon à ce que l’adversaire ne devine pas votre objectif. Il ne pourra alors pas prendre de précautions et vous pourrez utiliser ses réponses pour arriver à des conclusions différentes, voire opposées. Ceci est apparenté au stratagème 4 : cacher son jeu.', 1, 10, 2, 1),
(10, '2021-10-08 12:05:18', NULL, 'Stratagème X Prendre avantage de l’antithèse', 'Si vous vous rendez compte que votre adversaire répond par la négative à une question à laquelle vous avez besoin qu’il réponde par la positive dans votre argumentation, interrogez-le sur l’opposé de votre thèse, comme si c’était cela que vous vouliez lui faire approuver, ou donnez-lui le choix de choisir entre les deux afin qu’il ne sache pas à laquelle des deux propositions vous voulez qu’il adhère.', 'Si vous vous rendez compte que votre adversaire répond par la négative à une question à laquelle vous avez besoin qu’il réponde par la positive dans votre argumentation, interrogez-le sur l’opposé de votre thèse, comme si c’était cela que vous vouliez lui faire approuver, ou donnez-lui le choix de choisir entre les deux afin qu’il ne sache pas à laquelle des deux propositions vous voulez qu’il adhère.', 1, 10, 2, 1),
(11, '2021-10-08 12:05:41', NULL, 'Stratagème XI Généraliser ce qui porte sur des cas précis', 'Faites une induction et arrangez vous pour que votre adversaire concède des cas particuliers qui en découlent, sans lui dire la vérité générale que vous voulez lui faire admettre. Introduisez plus tard cette vérité comme un fait admis, et, sur le moment, il aura l’impression de l’avoir admise lui-même, et les auditeurs auront également cette impression car ils se souviendront des nombreuses questions sur les cas particuliers que vous aurez posées.', 'Faites une induction et arrangez vous pour que votre adversaire concède des cas particuliers qui en découlent, sans lui dire la vérité générale que vous voulez lui faire admettre. Introduisez plus tard cette vérité comme un fait admis, et, sur le moment, il aura l’impression de l’avoir admise lui-même, et les auditeurs auront également cette impression car ils se souviendront des nombreuses questions sur les cas particuliers que vous aurez posées.', 1, 10, 2, 1),
(12, '2021-10-08 12:05:59', NULL, 'Stratagème XII Choisir des métaphores favorables', 'Si la conversation porte autour d’une conception générale qui ne porte pas de nom mais requiert une désignation métaphorique, il faut choisir une métaphore favorable à votre thèse. Par exemple, les mots *serviles* et *liberales* utilisés pour désigner les partis politiques espagnols furent manifestement choisis par ces derniers.', 'Si la conversation porte autour d’une conception générale qui ne porte pas de nom mais requiert une désignation métaphorique, il faut choisir une métaphore favorable à votre thèse. Par exemple, les mots *serviles* et *liberales* utilisés pour désigner les partis politiques espagnols furent manifestement choisis par ces derniers.\n\nLe terme *protestant* fut choisi par les protestants, ainsi que le terme *évangéliste* par les évangélistes, mais les catholiques les appellent des *hérétiques*.\n\nOn peut agir de même pour les termes ayant des définitions plus précises, par exemple, si votre adversaire propose une *altération*, vous l’appellerez une « innovation » car ce terme est péjoratif. Si vous êtes celui qui fait une proposition, ce sera l’inverse. Dans le premier cas, vous vous référerez à votre adversaire comme étant « l’ordre établi », dans le second cas, comme « préjugé désuet ». Ce qu’une personne impartiale appellerait « culte » ou « pratique de la religion » serait désigné par un partisan comme « piété » ou « bénédiction divine » et par un adversaire comme « bigoterie » ou « superstition ». Au final, il s’agit là d’un *petitio principii* : ce qui n’a pas été démontré est utilisé comme postulat pour en tirer un jugement. Là où une personne parle de « mise en détention provisoire », une autre parlera de « mettre sous les verrous ». Un interlocuteur trahira ainsi souvent ses positions par les termes qu’il emploie. De tous les stratagèmes, celui-ci est le plus couramment utilisé et est utilisé d’instinct. L’un parlera de « prêtres » là où un autre parlera de « ratichons ». Zèle religieux = fanatisme, indiscrétion ou galanterie = adultère, équivoque = salace, embarras = banqueroute, « par l’influence et les connexions » = « par les pots-de-vin et le népotisme », « sincère gratitude » = « bon paiement », etc.', 1, 10, 2, 1),
(13, '2021-10-08 12:06:37', NULL, 'Stratagème XIII Faire rejeter l’antithèse', 'Pour que notre adversaire accepte une proposition, il faut également lui fournir la contre-proposition et lui donner le choix entre les deux.', 'Pour que notre adversaire accepte une proposition, il faut également lui fournir la contre-proposition et lui donner le choix entre les deux, en accentuant tellement le contraste que, pour éviter une position paradoxale, il se ralliera à notre proposition qui est celle qui paraît le plus probable. Par exemple, si vous voulez lui faire admettre qu’un garçon doit faire tout ce que son père lui dit de faire, posez lui la question : « Faut-il en toutes choses obéir ou bien désobéir à ses parents ? » De même, si l’on dit d’une chose qu’elle se déroule « souvent », demandez si par « souvent » il faut comprendre peu ou beaucoup de cas et il vous dira « beaucoup ». C’est comme si l’on plaçait du gris à côté du noir et qu’on l’appelait blanc, ou à côté du blanc et qu’on l’appelait noir.', 1, 10, 1, 1),
(14, '2021-10-08 12:07:00', NULL, 'Stratagème XIV Clamer victoire malgré la défaite', 'Il est un piège effronté que vous pouvez poser contre votre adversaire : lorsque votre adversaire aura répondu à plusieurs questions, sans qu’aucune des réponses ne se soient montrées favorables quant à la conclusion que vous défendez, présentez quand même votre conclusion triomphalement comme si votre adversaire l’avait prouvée pour vous.', 'Il est un piège effronté que vous pouvez poser contre votre adversaire : lorsque votre adversaire aura répondu à plusieurs questions, sans qu’aucune des réponses ne se soient montrées favorables quant à la conclusion que vous défendez, présentez quand même votre conclusion triomphalement comme si votre adversaire l’avait prouvée pour vous. Si votre adversaire est timide, ou stupide, et que vous vous montrez suffisamment audacieux et parlez suffisamment fort, cette astuce pourrait facilement réussir. Ce stratagème est apparenté au *fallacia non causæ ut causæ*.', 1, 10, 1, 1),
(15, '2021-10-08 12:10:15', '2021-10-08 12:13:35', 'Sass', 'Sass (Syntactically awesome stylesheets) est un langage de script préprocesseur qui est compilé ou interprété en CSS (Feuilles de styles en cascades). SassScript est le langage de script en lui-même.', 'Sass se compose de deux [syntaxes](https://fr.wikipedia.org/w/index.php?title=Syntaxe_(Informatique)&action=edit&redlink=1 \"Syntaxe (Informatique) (page inexistante)\"). La syntaxe originale, appelé \"la syntaxe indentée\"[3](https://fr.wikipedia.org/wiki/Sass_(langage)#cite_note-3) utilise l\'[indentation](https://fr.wikipedia.org/wiki/Style_d%27indentation \"Style d\'indentation\") pour séparer les blocs de code et les [sauts de ligne](https://fr.wikipedia.org/wiki/Fin_de_ligne \"Fin de ligne\") pour les séparer des règles. La nouvelle syntaxe, \"SCSS\", utilise les mêmes séparateurs de blocs que CSS. Les fichiers de la syntaxe indentée et SCSS utilisent respectivement les extensions `.sass` et `.scss`.\n\nLa syntaxe indentée est un [métalangage](https://fr.wikipedia.org/wiki/M%C3%A9talangage \"Métalangage\") et SCSS un métalangage imbriqué car un CSS valide est un SCSS valide sans modification de syntaxe[4](https://fr.wikipedia.org/wiki/Sass_(langage)#cite_note-4).\n\nSassScript fournit les mécanismes suivants : [variables](https://fr.wikipedia.org/wiki/Variable_(informatique) \"Variable (informatique)\"), imbrication, [mixins](https://fr.wikipedia.org/wiki/Mixin \"Mixin\") et [héritage](https://fr.wikipedia.org/wiki/H%C3%A9ritage_(informatique) \"Héritage (informatique)\") des sélecteurs.\n\nCaractéristiques\n----------------\n\n### Variables\n\nSass permet l\'utilisation de variables. Les variables doivent commencer par le [symbole dollar](https://fr.wikipedia.org/wiki/$#Informatique \"$\") (`$`). L\'[affectation](https://fr.wikipedia.org/wiki/Affectation_(informatique) \"Affectation (informatique)\") des variables se fait avec les [deux-points](https://fr.wikipedia.org/wiki/Deux-points \"Deux-points\") (`:`).[5](https://fr.wikipedia.org/wiki/Sass_(langage)#cite_note-5) Les variables peuvent être des arguments ou des résultats de l\'une des nombreuses fonctions disponibles[6](https://fr.wikipedia.org/wiki/Sass_(langage)#cite_note-6). Lors de la compilation les variables sont remplacées par leurs valeurs dans le fichier CSS de sortie.\n\n ```\n\r\n\n```\n\n ```\n\n```', 1, 9, 1, 1),
(16, '2021-10-08 12:14:37', NULL, 'Qu\'est-ce que PHP', '> PHP (officiellement, ce sigle est un acronyme récursif pour PHP Hypertext Preprocessor) est un langage de scripts généraliste et Open Source, spécialement conçu pour le développement d\'applications web. Il peut être intégré facilement au HTML.', 'Au lieu d\'utiliser des tonnes de commandes afin d\'afficher du HTML (comme en C ou en Perl), les pages PHP contiennent des fragments HTML dont du code qui fait \"quelque chose\" (dans ce cas, il va afficher `\"Bonjour, je suis un script PHP !\"`). Le code PHP est inclus entre [une balise de début `<?php` et une balise de fin `?>`](https://www.php.net/manual/fr/language.basic-syntax.phpmode.php) qui permettent au serveur web de passer en mode PHP.\n\nCe qui distingue PHP des langages de script comme le Javascript, est que le code est exécuté sur le serveur, générant ainsi le HTML, qui sera ensuite envoyé au client. Le client ne reçoit que le résultat du script, sans aucun moyen d\'avoir accès au code qui a produit ce résultat. Vous pouvez configurer votre serveur web afin qu\'il analyse tous vos fichiers HTML comme des fichiers PHP. Ainsi, il n\'y a aucun moyen de distinguer les pages qui sont produites dynamiquement des pages statiques.\n\nLe grand avantage de PHP est qu\'il est extrêmement simple pour les néophytes, mais offre des fonctionnalités avancées pour les experts. Ne craignez pas de lire la longue liste de fonctionnalités PHP. Vous pouvez vous plonger dans le code, et en quelques instants, écrire des scripts simples.\n\nBien que le développement de PHP soit orienté vers la programmation pour les sites web, vous pouvez en faire bien d\'autres usages. Lisez donc la section [Que peut faire PHP ?](https://www.php.net/manual/fr/intro-whatcando.php) ou bien le [tutoriel d\'introduction](https://www.php.net/manual/fr/tutorial.php) si vous êtes uniquement intéressé dans la programmation web.', 1, 3, 1, 1),
(17, '2021-10-08 12:15:43', '2021-10-08 12:16:53', 'Le Dodo', 'Le Dronte de Maurice1 (Raphus cucullatus2) est une espèce d\'oiseaux de l\'ordre des Columbiformes, endémique de l\'île Maurice3, disparue depuis le xvie siècle. Il est plus connu sous le nom de dodo, nom vernaculaire également utilisé pour désigner le Solitaire de Bourbon, bien que celui-ci appartienne à un autre ordre.', 'Apparenté au [Solitaire de Rodrigues](https://fr.wikipedia.org/wiki/Dronte_de_Rodrigues \"Dronte de Rodrigues\") et appartenant comme les [pigeons](https://fr.wikipedia.org/wiki/Columba_(oiseau) \"Columba (oiseau)\") à la [famille](https://fr.wikipedia.org/wiki/Famille_(biologie) \"Famille (biologie)\") des [Columbidae](https://fr.wikipedia.org/wiki/Columbidae \"Columbidae\"), ce [dronte](https://fr.wikipedia.org/wiki/Dronte \"Dronte\") vivait dans les forêts ou les plaines. Il mesurait environ un mètre pour une masse moyenne de 10,2 kilogrammes[4](https://fr.wikipedia.org/wiki/Dodo_(oiseau)#cite_note-Angst-4). Découvert en 1598, il était décrit comme lent, ne fuyant pas l\'être humain, gros et presque cubique. Son corps au plumage bleu gris était pourvu d\'ailes atrophiées jaune et blanc, ainsi que d\'un panache de quatre ou cinq plumes de mêmes couleurs en guise de queue. Ses pattes jaunes comportaient quatre doigts (trois à l\'avant et un à l\'arrière) terminés par de grands ongles noirs. Son bec crochu avait une tache bleue caractéristique à son extrémité et une rouge sur la mandibule inférieure. Sa tête noire ou grise possédait deux plis importants à la base du bec.\n\nLe dodo s\'est éteint moins d\'un siècle après sa découverte, à la fin du xviie siècle avec l\'arrivée des Européens. Il est aujourd\'hui souvent cité comme un [archétype](https://fr.wikipedia.org/wiki/Arch%C3%A9type_(philosophie) \"Archétype (philosophie)\") de l\'espèce éteinte car sa disparition, survenue à l\'[époque moderne](https://fr.wikipedia.org/wiki/%C3%89poque_moderne \"Époque moderne\"), est directement imputable à l\'activité humaine.', 1, 4, 1, 1),
(18, '2021-10-08 12:16:45', NULL, 'Le Ratel', 'Le ratel (Mellivora capensis), aussi appelé zorille du Cap, est un mustélidé répandu dans la région allant du Nord de l’Inde à la Péninsule arabique, et dans toute l’Afrique subsaharienne à l\'exception de Madagascar. Il est réputé pour son comportement féroce et particulièrement tenace ainsi que pour son endurance. C\'est la seule espèce actuelle du genre Mellivora.', 'C’est un petit [carnivore](https://fr.wikipedia.org/wiki/Carnivora \"Carnivora\") de la [famille](https://fr.wikipedia.org/wiki/Famille_(biologie) \"Famille (biologie)\") des [Mustélidés](https://fr.wikipedia.org/wiki/Mustelidae \"Mustelidae\"), mesurant environ 75 cm de long et 30 cm au [garrot](https://fr.wikipedia.org/wiki/Garrot_(anatomie) \"Garrot (anatomie)\") à l’âge adulte.\n\nIl est noir sur le ventre, les pattes, la [queue](https://fr.wikipedia.org/wiki/Queue_(anatomie) \"Queue (anatomie)\") et la partie inférieure de la tête jusqu’aux yeux. Il est blanc sur le crâne, du front jusqu’au haut du cou, et il est blanc-gris sur tout le dos. Le ratel possède des [griffes](https://fr.wikipedia.org/wiki/Griffe_(anatomie) \"Griffe (anatomie)\") d’environ 4 cm de long à l’âge adulte. Son odorat ainsi que son ouïe sont très sensibles et lui permettent de chasser ses proies sur l\'étendue de son territoire.\n\nLe mâle adulte pèse environ 12 kg, mais la femelle ne dépasse pas les 6 kg. Sa longévité à l’état sauvage n’est pas connue, mais il peut vivre jusqu’à 26 ans en captivité.', 1, 4, 1, 1),
(19, '2021-10-08 12:31:23', '2021-10-08 12:31:36', 'TinyMCE', 'TinyMCE, aussi connu sous le nom de Tiny Moxiecode Content Editor (Éditeur de contenu de Moxiecode de Tiny) est un éditeur de HTML de type WYSIWYG.', 'Fonctionnalités de base\n-----------------------\n\nÉcrit en [JavaScript](https://fr.wikipedia.org/wiki/JavaScript \"JavaScript\"), indépendant de la plate-forme, basé sur le Web et publié comme logiciel [open source](https://fr.wikipedia.org/wiki/Open_source \"Open source\") sous la licence [LGPL](https://fr.wikipedia.org/wiki/LGPL \"LGPL\"), initialement par [Moxiecode Systems AB](https://fr.wikipedia.org/w/index.php?title=Moxiecode&action=edit&redlink=1 \"Moxiecode (page inexistante)\"), société rachetée par [Ephox](https://fr.wikipedia.org/w/index.php?title=Ephox&action=edit&redlink=1 \"Ephox (page inexistante)\") devenu [Tiny Technologies, Inc](https://fr.wikipedia.org/w/index.php?title=Tiny_Technologies&action=edit&redlink=1 \"Tiny Technologies (page inexistante)\"). Il est capable de convertir les champs HTML *textarea* ou d\'autres éléments HTML en instances de l\'éditeur. TinyMCE est conçu pour s\'intégrer facilement aux [systèmes de gestion de contenu](https://fr.wikipedia.org/wiki/Syst%C3%A8mes_de_gestion_de_contenu \"Systèmes de gestion de contenu\") (SGC). C\'était l\'éditeur de contenu par défaut de [WordPress](https://fr.wikipedia.org/wiki/WordPress \"WordPress\") jusqu’au lancement de l’Éditeur de Blocs[2](https://fr.wikipedia.org/wiki/TinyMCE#cite_note-2) et de [Joomla!](https://fr.wikipedia.org/wiki/Joomla! \"Joomla!\")[3](https://fr.wikipedia.org/wiki/TinyMCE#cite_note-3), les deux SGC les plus utilisés[4](https://fr.wikipedia.org/wiki/TinyMCE#cite_note-4), et du SGC [Plone](https://fr.wikipedia.org/wiki/Plone \"Plone\") à partir de la version 4[5](https://fr.wikipedia.org/wiki/TinyMCE#cite_note-5).\n\nL\'éditeur propose des outils de mise en forme HTML, comme [gras](https://fr.wikipedia.org/wiki/Graisse_(typographie) \"Graisse (typographie)\"), [italique](https://fr.wikipedia.org/wiki/Italique_(typographie) \"Italique (typographie)\"), soulignement, des listes numérotées et des listes à puces, les différents types d\'alignements, le placement en ligne d\'images et de vidéos, etc; il permet aux utilisateurs d\'un site web d\'éditer des documents HTML en ligne. Les différentes options peuvent être configurées lors de l\'intégration à un projet, ce qui améliore la flexibilité du projet.\n\nCompatibilité navigateur\n------------------------\n\nTinyMCE est compatible avec plusieurs navigateurs, y compris [Internet Explorer](https://fr.wikipedia.org/wiki/Internet_Explorer \"Internet Explorer\"), [Mozilla Firefox](https://fr.wikipedia.org/wiki/Mozilla_Firefox \"Mozilla Firefox\"), [Safari](https://fr.wikipedia.org/wiki/Safari_(logiciel) \"Safari (logiciel)\"), [Opera](https://fr.wikipedia.org/wiki/Opera \"Opera\") et [Google Chrome](https://fr.wikipedia.org/wiki/Google_Chrome \"Google Chrome\"), sur plusieurs [systèmes d\'exploitation](https://fr.wikipedia.org/wiki/Syst%C3%A8mes_d%27exploitation \"Systèmes d\'exploitation\")[6](https://fr.wikipedia.org/wiki/TinyMCE#cite_note-6).\n\nAPI\n---\n\nTinyMCE comprend une vaste [API](https://fr.wikipedia.org/wiki/Interface_de_programmation \"Interface de programmation\") pour une intégration personnalisée[7](https://fr.wikipedia.org/wiki/TinyMCE#cite_note-7).\n\nPlugins\n-------\n\nTinyMCE est livré avec un assortiment de plugins[8](https://fr.wikipedia.org/wiki/TinyMCE#cite_note-8). Parce que TinyMCE est censé être une application côté client, il ne comprend pas les gestionnaires de fichiers natifs pour les diverses technologies côté serveur. Plusieurs solutions de gestionnaire de fichiers existent, y compris un projet propriétaire développé par Moxiecode Systems AB, ainsi qu\'une poignée de solutions open source.\n\n### Propriétaires\n\n- [MoxieManager](http://www.moxiemanager.com/)qui remplace les anciens MCFileManager et MCImageManager\n- [TinyBrowser](http://www.lunarvis.com/products/tinymcefilebrowserwithupload.php) — Gestionnaire de fichiers avec téléchargement simple de plus d\'un fichier, compatible avec la version 3.x uniquement (Le chargeur utilise [Adobe Flash](https://fr.wikipedia.org/wiki/Adobe_Flash \"Adobe Flash\")).', 1, 2, 1, 1),
(20, NULL, NULL, 'Sous quelle licence partager mon CSS ', 'Faire le choix d’une licence est important dès lors que l’on souhaite partager du code. En effet, en l’absence de licence, le code est uniquement soumis au droit d’auteur et tout ce qui n’est pas explicitement autorisé, est interdit : il n’est donc pas réutilisable. Le choix d’une licence Open Source permet de partager son code en précisant les conditions de réutilisation. Mais laquelle choisir ?', 'Je contribuais jusqu’alors à un projet libre, en open source, sans me poser précisément la question de la licence sous laquelle mon code était partagé, le déposant sur un espace communautaire dit « libre au sens de GNU ». Je suppose que j’héritais de la [GNU General Public License](http://www.april.org/gnu/gpl_french2.html).\n\nMais hors de ce contexte précis, sous quelle licence publier mon CSS, par exemple sur GitHub ? Ce service de développement logiciel en ligne aide au choix d’une licence, lors de la création d’un nouveau répertoire, grâce à ce site dédié, assez clair :\n\nJ’aime beaucoup les [licences Creatives Commons](http://creativecommons.fr/licences/les-6-licences/) parce que, présentées de façon claire et pédagogique, traduites en de nombreuses langues, elles ont le mérite d’être compréhensibles, donc répandues et connues d’un large public, et semblent échapper aux ergotages libristes qui me rendent chèvre. Mais ces licences sont faites pour tout ce qui n’est pas du code : des photos, comme sur Flickr, des recettes, comme sur Cuisine-libre.org, etc. Elles ne sont [pas conseillées pour du code logiciel](http://wiki.creativecommons.org/FAQ#Can_I_use_a_Creative_Commons_license_for_software.3F). La CC-by-sa que j’affectionne n’est donc pas le bon choix pour mon code. D’autres arguent qu’au contraire, les langages HTML et CSS n’étant pas considérés comme du code, mais plutôt comme du texte — comme les mots dans un roman, ce sont toujours un peu les mêmes déclarations CSS et balises HTML qui sont répétées — relèvent bien des licences CC. Arf, toujours ce vieux débat sur le statut des langages ! Je code des trucs bien galère à caler, que la plupart des développeurs sont infichus de faire correctement et sont donc bien contents de récupérer prêts-à-l’emploi, mais qu’ils déconsidèrent comme trop trivial… Allez donc vous pignoler ailleurs, ouste !\n\nQue font les autres ? Quelles licences sont choisies pour partager les projets CSS ? Le fameux *Bootstrap* est sous [Apache License](https://github.com/twbs/bootstrap/blob/master/LICENSE), incorporant *Normalize* sous [MIT](https://github.com/necolas/normalize.css/blob/master/LICENSE.md) comme *Boilerplate* et *Foundation*, tandis qu’*OOCSS* est sous [BSD License](https://github.com/stubbornella/oocss/blob/master/LICENSE), *RÖCSSTI* sous [CC-by](https://github.com/nico3333fr/ROCSSTI/pull/2), *KNACSS* sous [WTFPL](https://github.com/raphaelgoetter/KNACSS/issues/36) et le reset de Meyer est dans le domaine public. Dans les [galeries de thèmes](http://www.eclaireur.net/technique/galleries-css-telecharger-des-themes-libres-de-droits/) comme [Open Designs](http://www.opendesigns.org/help/), les gabarits HTML/CSS/JS sont distribués sous des licences aussi variées : CC, GPL, etc. Bref, les licences prolifèrent, diverses, nombreuses et même versionnées, mais peu traduites. Problème : on n’y comprend rien.\n\nDans son lightning talk, à Paris Web 2012, « [Plus de code, moins de licence](http://www.paris-web.fr/2012/conferences/lightning-talks.php) » (à partir de 38:20), Thomas Zilliox, « un mec qui n’y connaît rien » comme moi, fait le point parmi les [licences Open Source](http://opensource.org/licenses/alphabetical). Pour résumer :\n\n1. Si vous ne voulez que le minimum incontournable, c’est-à-dire garder votre nom : MIT, BSD 3, Apache 2\n2. Si vous voulez en plus que les modifications soient reversées au projet : LGPL, MPL 2, CDDL, EPL\n3. Si vous voulez qu’en plus tout reste libre : GPL\n \nTout s’éclaire ! J’ai lu tout ce que je pouvais et c’est finalement la MIT qui me semble être la plus adaptée à mon CSS. Simple et suffisante.\n\nhttp://romy.tetue.net/sous-quelle-licence-partager-mon-css', 1, 8, 3, 1),
(21, '2021-10-08 12:47:00', NULL, 'Fake Facebook Post Generator', 'Fake Facebook Post Generator used by celebrities and media to make a fake facebook post. Create your own fake facebook post using our Facebook Post Generator and prank your friends. Create most viral fake facebook posts with our Fake Facebook Account Creator. Lets get started and create fake facebook post that will amaze everyone and increase the engagement of your facebook.', 'Fake Facebook Account Creator\n-----------------------------\n\nAn awesome tool for you to create fake facebook account online. Our Fake Facebook Account Generator allows you to change the person’s name, profiles pictures, likes, post text, post image, and comments as you desire so that post should look real. Hence you can easily prank your friends and family members with your fake facebook post and see their awesome reactions. Also, use emojis and smileys to make it look like real facebook post. It depends on your creativity how you can astonish and prank your friends and family. Please note that these our Facebook Post Generator is not associated with [Facebook](https://facebook.com/). Only use it for fun and personal use, don’t hurt others.\n\n#### Make Fake Instagram Direct Messages in Seconds using our [Fake Instagram Direct Message Generator](https://generatestatus.com/fake-instagram-direct-message/).\n\n### Advantages of Using Fake Facebook Post Maker\n\nFacebook is one of the most popular platforms today that allows online thought sharing and social networking. You can make a fake facebook post easily with our Fake Facebook Generator. It is a fast, beautiful and fun way to share your life with friends and family.\n\n##### You may also like: [Write Names on Birthday Cakes, Wishes and Greetings – Makebirthdaycakes.com](https://makebirthdaycakes.com/)\n\nYou can also advertise your products if you have some business or shops and share the generated facebook post on social media. It can help you gain more customers and be more attractive because you can add thousands of likes and celebrity comments. In this way, you can make a fake facebook post more engaging and viral. People will take more interest in it when you share it on social media platforms. Hence in this way, you will get more boost to your business and content marketing.\n\n###### Enjoy making fake facebook posts for fun and keep sharing our Fake Facebook Generator with your friends and family', 1, 1, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `article_status`
--

CREATE TABLE `article_status` (
  `id` int(1) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article_status`
--

INSERT INTO `article_status` (`id`, `name`) VALUES
(2, 'private'),
(1, 'published'),
(3, 'standby');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(3) NOT NULL,
  `name` varchar(31) NOT NULL,
  `parent_id` int(3) DEFAULT NULL COMMENT 'parent category'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `parent_id`) VALUES
(1, 'aucune', NULL),
(2, 'javascript', NULL),
(3, 'php', NULL),
(4, 'nature', NULL),
(5, 'politique', NULL),
(6, 'philosophie', NULL),
(7, 'bootstrap', 8),
(8, 'css', NULL),
(9, 'scss', 8),
(10, 'schopenhauer', 6);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(8) NOT NULL,
  `user_id` int(6) DEFAULT NULL,
  `article_id` int(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `content` text NOT NULL,
  `validated` tinyint(1) NOT NULL,
  `answer_to` int(8) DEFAULT NULL COMMENT 'id of the answered comment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `article_id`, `created_at`, `last_modified`, `content`, `validated`, `answer_to`) VALUES
(1, 1, 19, '2021-10-08 12:36:38', NULL, 'Très pratique comme outil pour faire une mise en forme simple !', 1, NULL),
(2, 1, 18, '2021-10-08 12:37:03', NULL, 'Honey Badger en anglais', 1, NULL),
(3, 1, 17, '2021-10-08 12:37:29', '2021-10-08 12:37:44', ' Le dodo s\'est éteint moins d\'un siècle après sa découverte\r\nDur...', 1, NULL),
(5, 2, 19, '2021-10-08 12:39:57', NULL, 'C\'est pas faux', 1, 1),
(6, 2, 16, '2021-10-08 12:40:36', NULL, 'C\'est top !', 1, NULL),
(7, 6, 19, '2021-10-08 12:41:33', NULL, 'Bien joué Perceval', 1, 1),
(8, 6, 18, '2021-10-08 12:43:10', NULL, 'ça a l\'air costaud ', 1, NULL),
(9, 23, 16, '2021-10-08 12:44:54', NULL, 'C’est un super article qui résume bien le problème et les solutions !\r\nJe suis bien content que mon lightning talk ait pu t’aider ;)\r\nA bientôt\r\n', 1, NULL),
(10, 23, 21, '2021-10-08 12:47:29', NULL, 'J\'adore', 0, NULL),
(11, 23, 15, '2021-10-08 12:47:39', NULL, 'J\'adore', 0, NULL),
(12, 23, 13, '2021-10-08 12:48:01', NULL, 'J\'aime pas trop, c\'est compliqué', 0, NULL),
(13, 23, 9, '2021-10-08 12:49:51', NULL, 'I\'ll never understand how Taylor rocks every single outfit she wears', 0, NULL),
(18, 13, 18, '2021-10-08 12:52:25', NULL, '3 billion.. ????????', 0, NULL),
(19, 13, 12, '2021-10-08 12:52:48', NULL, 'Sacré pavé', 0, NULL),
(20, 15, 19, '2021-10-08 12:55:29', NULL, 'vive TinyMCE. Mais c\'est vraiment super lourd, un simple parser markdown est tellement mieux..', 1, NULL),
(21, 15, 19, '2021-10-08 12:58:17', NULL, 'What do you like best?\r\nTinyMCE has enabled me to develop high quality content for web on blog posts.\r\n\r\nIt has alot of css templates that ease web design and development.\r\nReview collected by and hosted on G2.com.\r\n\r\nWhat do you dislike?\r\nI do not like that TinyMCE fails to integrate with Filezilla to ease file upload.', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `reaction`
--

CREATE TABLE `reaction` (
  `article_id` int(4) NOT NULL,
  `user_id` int(6) NOT NULL,
  `reaction_id` int(3) NOT NULL,
  `comment_id` int(6) DEFAULT NULL COMMENT 'if related to a comment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `reaction_list`
--

CREATE TABLE `reaction_list` (
  `id` int(3) NOT NULL,
  `name` varchar(31) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(6) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `firstname` varchar(63) NOT NULL,
  `lastname` varchar(63) NOT NULL,
  `pseudo` varchar(63) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(63) NOT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `role_id` int(1) NOT NULL,
  `status_id` int(1) NOT NULL COMMENT '1 : offline\r\n2 : online\r\n3 :  banned',
  `score` int(8) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `created_at`, `firstname`, `lastname`, `pseudo`, `password`, `email`, `phone`, `role_id`, `status_id`, `score`) VALUES
(1, '2021-05-01 12:00:00', 'admin', 'admin', 'admin', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'admin.nimda@mail.com', NULL, 1, 2, 1000),
(2, '2021-08-28 22:17:59', 'Étienne', 'Deschamps', 'Étienne58', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Étienne.Deschamps@mail.fr', '0696677473', 2, 1, 7),
(3, '2021-08-17 20:06:11', 'Olivie', 'Hoarau', 'Olivie62', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Olivie.Hoarau@mail.fr', '0662226547', 2, 1, 17),
(4, '2021-09-15 14:36:46', 'Noël', 'de Petit', 'Noël24', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Noël.dePetit@mail.net', '0667629068', 2, 1, 69),
(5, '2021-09-18 01:16:52', 'Luce', 'Monnier', 'Luce7', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Luce.Monnier@mail.com', '0659573300', 2, 1, 88),
(6, '2021-08-26 03:30:21', 'Catherine', 'Valette-Benard', 'Catherine21', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Catherine.Valette-Benard@mail.fr', '0601458368', 2, 1, 29),
(7, '2021-09-05 15:21:46', 'Michèle', 'Hardy', 'Michèle44', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Michèle.Hardy@mail.com', '0651173519', 2, 1, 24),
(8, '2021-08-21 01:32:18', 'Dominique', 'Mercier', 'Dominique99', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Dominique.Mercier@mail.fr', '0637370108', 2, 1, 30),
(9, '2021-09-23 18:28:12', 'Alfred', 'Carpentier', 'Alfred35', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Alfred.Carpentier@mail.net', '0672131300', 2, 1, 13),
(10, '2021-08-29 13:54:31', 'Léon', 'Bailly', 'Léon64', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Léon.Bailly@mail.net', '0640277032', 2, 1, 7),
(11, '2021-10-04 12:26:57', 'Mathilde', 'Merle', 'Mathilde20', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Mathilde.Merle@mail.net', '0642311826', 2, 1, 67),
(12, '2021-09-27 08:38:01', 'Michèle', 'Petitjean', 'Michèle18', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Michèle.Petitjean@mail.fr', '0605558295', 2, 1, 68),
(13, '2021-08-19 12:19:44', 'Lucie', 'Carlier-Hamel', 'Lucie4', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Lucie.Carlier-Hamel@mail.com', '0611336887', 2, 1, 89),
(14, '2021-09-16 18:53:26', 'Anaïs', 'Lenoir de Bouvet', 'Anaïs35', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Anaïs.LenoirdeBouvet@mail.com', '0639077861', 2, 1, 55),
(15, '2021-08-18 23:08:52', 'Bertrand', 'Roger', 'Bertrand68', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Bertrand.Roger@mail.fr', '0699732699', 2, 2, 97),
(16, '2021-09-25 14:20:50', 'Hortense', 'Barre', 'Hortense55', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Hortense.Barre@mail.net', '0681991357', 2, 1, 50),
(17, '2021-08-21 09:57:31', 'Bertrand', 'Bigot', 'Bertrand38', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Bertrand.Bigot@mail.net', '0644095383', 2, 1, 28),
(18, '2021-09-06 10:03:32', 'Gabriel', 'Gerard', 'Gabriel28', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Gabriel.Gerard@mail.com', '0641265522', 2, 1, 60),
(19, '2021-08-14 06:51:00', 'Aurélie', 'Levy', 'Aurélie63', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Aurélie.Levy@mail.net', '0654984361', 2, 1, 21),
(20, '2021-09-19 11:11:39', 'Théophile', 'Michel', 'Théophile33', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Théophile.Michel@mail.com', '0693372059', 2, 1, 22),
(21, '2021-09-08 05:03:23', 'Vincent', 'Courtois', 'Vincent14', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Vincent.Courtois@mail.net', '0612117276', 2, 1, 7),
(22, '2021-08-11 20:58:59', 'Henri', 'Vallee-Marty', 'Henri70', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Henri.Vallee-Marty@mail.net', '0650877169', 2, 1, 5),
(23, '2021-09-04 02:27:44', 'Gabriel', 'Mace', 'Gabriel29', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Gabriel.Mace@mail.net', '0655665170', 2, 1, 40),
(24, '2021-10-03 16:53:03', 'Jérôme', 'Didier', 'Jérôme20', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Jérôme.Didier@mail.net', '0638606647', 2, 1, 30),
(25, '2021-09-23 22:39:10', 'Thierry', 'Dupuy-Legrand', 'Thierry78', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Thierry.Dupuy-Legrand@mail.com', '0662056781', 2, 1, 97),
(26, '2021-09-11 00:19:34', 'Georges', 'Roussel', 'Georges48', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Georges.Roussel@mail.net', '0698112246', 2, 1, 24),
(27, '2021-09-24 22:12:24', 'Margot', 'Paul', 'Margot35', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Margot.Paul@mail.com', '0624025474', 2, 1, 40),
(28, '2021-08-16 20:04:45', 'Michel', 'Humbert', 'Michel14', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Michel.Humbert@mail.net', '0657050919', 2, 1, 94),
(29, '2021-08-16 18:30:04', 'Frédéric', 'Robin', 'Frédéric11', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Frédéric.Robin@mail.net', '0669900416', 2, 1, 28),
(30, '2021-08-18 22:26:20', 'Françoise', 'Martel', 'Françoise76', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Françoise.Martel@mail.net', '0628184938', 2, 1, 12),
(31, '2021-08-29 17:28:11', 'Camille', 'Bonnet Le Ferreira', 'Camille68', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Camille.BonnetLeFerreira@mail.com', '0641896478', 2, 1, 6),
(33, '2021-09-20 11:46:25', 'Charles', 'Couturier', 'Charles54', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Charles.Couturier@mail.com', '0606235514', 2, 1, 76),
(34, '2021-09-11 03:45:36', 'Christophe', 'Launay', 'Christophe33', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Christophe.Launay@mail.fr', '0665802234', 2, 1, 80),
(35, '2021-08-30 20:39:33', 'Paul', 'Evrard', 'Paul70', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Paul.Evrard@mail.com', '0691840320', 2, 1, 76),
(36, '2021-10-04 15:13:12', 'Emmanuelle', 'Marin', 'Emmanuelle50', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Emmanuelle.Marin@mail.com', '0615281801', 2, 1, 66),
(37, '2021-09-25 01:54:48', 'Guy', 'Rocher', 'Guy83', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Guy.Rocher@mail.net', '0661442417', 2, 1, 95),
(38, '2021-08-14 00:52:44', 'Raymond', 'Lambert-Pichon', 'Raymond80', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Raymond.Lambert-Pichon@mail.com', '0690661688', 2, 1, 1),
(39, '2021-08-19 15:49:48', 'Susanne', 'Gregoire', 'Susanne15', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Susanne.Gregoire@mail.com', '0626091584', 2, 1, 39),
(40, '2021-09-08 01:36:07', 'Pierre', 'du Costa', 'Pierre92', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Pierre.duCosta@mail.fr', '0670783774', 2, 1, 97),
(41, '2021-08-23 14:11:04', 'Guillaume', 'Blot Le Labbe', 'Guillaume55', '$2y$10$336vN9uJZSHMOWmTErNPeuQ3en8nhwKAXBSDuyhPU1rSeXkERjtsW', 'Guillaume.BlotLeLabbe@mail.net', '0676090672', 2, 1, 82);

-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(1) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user_role`
--

INSERT INTO `user_role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'user'),
(3, 'editor'),
(4, 'moderator');

-- --------------------------------------------------------

--
-- Structure de la table `user_status`
--

CREATE TABLE `user_status` (
  `id` int(1) NOT NULL,
  `name` varchar(31) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user_status`
--

INSERT INTO `user_status` (`id`, `name`) VALUES
(1, 'offline'),
(2, 'online'),
(3, 'banned'),
(4, 'deleted');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Index pour la table `article_status`
--
ALTER TABLE `article_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `answer_to` (`answer_to`);

--
-- Index pour la table `reaction`
--
ALTER TABLE `reaction`
  ADD KEY `article_id` (`article_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `reaction_id` (`reaction_id`);

--
-- Index pour la table `reaction_list`
--
ALTER TABLE `reaction_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD KEY `status` (`status_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Index pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_status`
--
ALTER TABLE `user_status`
  ADD PRIMARY KEY (`id`,`name`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `reaction_list`
--
ALTER TABLE `reaction_list`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `article_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `article_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `article_status` (`id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`answer_to`) REFERENCES `comment` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Contraintes pour la table `reaction`
--
ALTER TABLE `reaction`
  ADD CONSTRAINT `reaction_ibfk_1` FOREIGN KEY (`reaction_id`) REFERENCES `reaction_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reaction_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `reaction_ibfk_3` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `reaction_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `user_status` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
