# N'Blog
[N'Blog](https://nblog.idrissa-sall.com/) ou encore Nabile'Blog est un blog en ligne et personnel, développé en PHP et principalement avec les outils Symfony, avec une base de données MySQL. A travers de ce blog je souhaite partager mes connaissances et mes expériences dans les domaines qui m'intéressent. Ce blog est composé principalement de deux parties: Admin c'est à dire moi et les Users c'est à dire les lecteurs.  

## Admin
L'admin à travers une interface gère tout ce qui est lié aux postes à travers de trois fonctionnalités principales comme :

### Les postes
Dans la barre de menu, en cliquant sur liste des postes, on a la liste de tous les articles. Cette fonctionnalité permet à l'admin de pouvoir créer de nouveaux articles, de choisir des [catégories](#les-catégories) au moment de la création, de supprimer un a article, de le modifier (son titre, le contenu et les catégories).

### Les catégories
Les blogs ont besoins d'étre classés pour simplifier leur classement et leur lecture. Cette classification permet de regrouper les blogs par catégories. Ainsi l'admin peut ajouter des catégories commes "Sport", "Formule 1" etc.. Au moment de la création d'un article on peut choisir parmi une liste, de cocher la ou les catégories qu'il veut donner à ce post. Ainsi depuis le menu des catégories, on a la liste de toutes les catégories, l'admin peut ajouter de nouvelles catégories, modifier les catégories, de les supprimer, d'afficher une catégorie en particulier et de voir tous les articles qui ont cette catégorie.

### Les commentaires
Une interface est dédiée à l'admin pour avoir la liste de tous les commentaires sur tous les articles.  
Pour un commentaire donné, nous avons un lien vers cet article, le nom de l'auteur, le contenu du commentaire, la date du commentaire et une action pour supprimer le commentaire.

## Users
Ce sont eux les internautes. Dans la page d'acceuil, ils ont un bref résumé du blog avce la possibilité de cliquer sur les catégories présentes et de voir les articles associés. Un peu plus bas, ils ont la liste des 6 derniers articles avec un petit résumé des article ainsi que le choix de "Lire la suite". Ils peuvent aussi créer ou non des comptes dans la patie "Connexion".

### Les comptes
Tout user le désirant peut créer un compte avec peu d'informations. Après la création de leur compte, ils ont la possibilité de modifier leurs informations dans le menu "profile" où ils ont également la possibilté de voir toutes les informations dont nous disposons sur eux. Au moment de la modification, ils ont également le droit de supprimer définitivement leur compte.

### Les postes
Les users peuvent lire, s'ils le souhaites, les articles les plus récents ou bien de choisir depuis la barre de menu "Liste de Postes" et voir ainsi tous les articles écrits par ordre du plus anciens aux plus récents. Pour chaque articles, ils peuvent lire le contenu, de savoir dans quelle catégorie il fait partie, quand l'article a été publié, et par extension la possibilité de faires des [commentaires](#les-commentaires).

### Les commentaires
Ils peuvent également laisser un commentaire avec leurs noms et sous le pseudo "Anonyme" si aucun compte n'est connecté, lire les anciens commentaire des postes et connaitre leurs auteurs. Ceci dit à chaque post qu'ils lisent, ils ont tous les commentaires liés à ce post. Ainsi ils peuvent partager leurs connaissances et leurs expériences relatives aux articles publiés.
