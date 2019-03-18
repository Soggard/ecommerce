

#Environnement
#####Pour lancer le serveur :
>php bin/console server:run

#####Pour démarrer la base de données après avoir lancé Docker
>docker build .

>docker-compose up

#####Pour initialiser la base de données
>php bin/console doctrine:database:create 

>php bin/console doctrine:schema:update --force

#Commandes

##### Créer une entité
>php bin/console make:entity

##### Exécuter les fixtures load
>php bin/console make:fixtures

>php bin/console doctrine:fixtures:load
