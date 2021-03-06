# SciMS - Un CMS open-source pour les scientifiques [![Build Status](https://travis-ci.org/rouen-ssi/scims-api.svg?branch=master)](https://travis-ci.org/rouen-ssi/scims-api)

Ce dépôt est le backend du projet [SciMS](https://github.com/rouenssi-tnp/scims).

## Installation

Avant de commencer, installez [Composer](https://getcomposer.org/).

Toute l'installation est automatisée grâce à Composer.
Ce script installera toutes les dépendances et générera les scripts SQL.
```
composer install --no-dev
```

Vous devez maintenant créer la base de données SQLite. Le fichier sera placé dans la racine du projet.
```
composer install-db --no-dev
```
**Attention** Si une base de données existait déjà, toutes ses données seront effacées (voir <a href="#migration">Migration</a>)

<a name="migration"></a>
## Migration

Chaque fois que vous ajoutez des classes PHP ou modifiez le schéma de la base de données, vous devez indiquer à Propel d'effectuer une migration puis d'indiquer à Composer de rafraîchir son cache.
```
composer migrate
```
