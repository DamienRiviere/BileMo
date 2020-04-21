# Créez un web service exposant une API

## Technologie utilisée

- Symfony 4
- PHP
- Behat
- JWT Authentication
- Swagger
- Architecture ADR (Action - Domain - Responder)

## Installation du projet

En fonction de votre système d'exploitation plusieurs serveurs peuvent être installés :

     - Windows : WAMP (http://www.wampserver.com/)
     - MAC : MAMP (https://www.mamp.info/en/mamp/)
     - Linux : LAMP (https://doc.ubuntu-fr.org/lamp)
     - XAMP (https://www.apachefriends.org/fr/index.html)
     - SQLite (https://www.sqlite.org/download.html)
  
## Clonage du projet

Installation de GIT : 

    - GIT (https://git-scm.com/downloads) 

Une fois GIT installé, il faudra vous placer dans le répertoire de votre choix puis exécuté la commande suivante :

    - git clone https://github.com/DamienRiviere/BileMo.git
    
Le projet sera automatiquement copié dans le répertoire ciblé.

## Configuration des variables d'environnement

Configurez les variables d'environnement comme la connexion à la base de données dans le fichier env.local qui sera créé à la racine du projet en copiant le fichier .env. Vous pourrez ensuite renseigner les identifiants de votre base de données en suivant le modèle ci-dessous.

    - DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
    - DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

## Création de la base de données

Créez la base de données de l'application en tapant la commande ci-dessous :

    - php bin/console doctrine:database:create

Puis lancer la migration pour créer les tables dans la base de données :

    - php bin/console doctrine:migrations:migrate    
    
## Lancement du serveur

Vous pouvez lancer le serveur via la commande suivante :

    - php bin/console server:run
    
## Générer des fausses données

Vous pouvez générer des fausses données grâce la fixture présente dans le projet avec la commande suivante :

    - php bin/console doctrine:fixtures:load
    
## Installation de Postman

Pour interagir avec les API, vous pouvez installer Postman :

    - Postman (https://www.postman.com/)
    
Tutoriels Postman : 

    - https://www.postman.com/resources/videos-tutorials/
    
## Documentation

Vous pouvez accéder à la documentation des API à l'adresse suivante en locale :

    - http://127.0.0.1:8000/api/doc
    
Ou en ligne :

    - https://bilemo-dre.herokuapp.com/api/doc

# Description du projet

## Contexte

BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.

Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo. Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Il va falloir que vous exposiez un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.

## Besoin client

Le premier client a enfin signé un contrat de partenariat avec BileMo ! C’est le branle-bas de combat pour répondre aux besoins de ce premier client qui va permettre de mettre en place l’ensemble des API et de les éprouver tout de suite.

 Après une réunion dense avec le client, il a été identifié un certain nombre d’informations. Il doit être possible de :

    - consulter la liste des produits BileMo ;
    - consulter les détails d’un produit BileMo ;
    - consulter la liste des utilisateurs inscrits liés à un client sur le site web ;
    - consulter le détail d’un utilisateur inscrit lié à un client ;
    - ajouter un nouvel utilisateur lié à un client ;
    - supprimer un utilisateur ajouté par un client.

Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT.

Vous avez le choix entre mettre en place un serveur OAuth et y faire appel (en utilisant le FOSOAuthServerBundle), et utiliser Facebook, Google ou LinkedIn. Si vous décidez d’utiliser JWT, il vous faudra vérifier la validité du token ; l’usage d’une librairie est autorisé.

## Présentation des données

Le premier partenaire de BileMo est très exigeant : il requiert que vous exposiez vos données en suivant les règles des niveaux 1, 2 et 3 du modèle de Richardson. Il a demandé à ce que vous serviez les données en JSON. Si possible, le client souhaite que les réponses soient mises en cache afin d’optimiser les performances des requêtes en direction de l’API.

