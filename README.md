# SciMS - Un CMS open-source pour les scientifiques

Ce dépôt est le backend du projet [SciMS](https://github.com/rouenssi-tnp/scims).

## Installation

Toute l'installation est automatisée grâce à Composer. Pour lancer l'installation :
```
php composer.phar install
```

Le script installera toutes les dépendances, générera la base de données, les classes PHP et les fichiers de configuration.

### Tester l'installation
Vous devez maintenant tester votre installation. Déplacez-vous dans le dossier `app/` puis exécutez :
```
cd app/
php -S localhost:8080
```

Avec votre navigateur, rendez-vous à l'adresse [http://localhost:8080/test.php](http://localhost:8080). Si aucune erreur ne s'affiche ni dans votre navigateur, ni dans votre console, votre installation fonctionne parfaitement.
