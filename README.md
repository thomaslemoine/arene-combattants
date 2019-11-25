# Arène de combattants

[Sujet](https://github.com/thomaslemoine/arene-combattants/blob/master/TP%20Symfony.pdf)

# Installer le projet

### 1. Clone le projet
~~~
git clone https://github.com/thomaslemoine/arene-combattants.git
~~~
~~~
cd arene-combattants
~~~
### 2. Installer Composer
~~~
composer install
~~~
### 3. Preparer la database

Modifier le fichier .env
~~~
php bin/console doctrine:database:create
~~~
~~~
php bin/console doctrine:migrations:migrate
~~~
### 4. Générer les données
~~~
php bin/console doctrine:fixtures:load
~~~
