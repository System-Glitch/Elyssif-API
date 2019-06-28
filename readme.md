<h1 height="256"><img align="left" width="41" height="41" src=".github/logo48.png">&nbsp;Elyssif API</h1>

**E**lyssif **L**et's **Y**ou **S**ecurely **S**end **I**mportant **F**iles

Elyssif is a user-friendly app that allows you to send sensitive or important files via the platform of your choice without worrying about your data being stolen or sold, thanks to strong asymmetric encryption. This repository holds the webservice (REST APIà to make this application work.

## Installing

### Prerequisites

* [Git](https://git-scm.com)
* PHP >= 7.2
* MariaDB server >= 10.3
* Apache 2 (or Nginx)
* Composer
* Supervisor
* npm >= 6.9.0
* node >= v10.15.0
* [VirtualBox](https://www.virtualbox.org/) and [Vagrant](https://www.vagrantup.com/) (Optional but recommended)
* [Postman](https://www.getpostman.com/) (Optional but recommended)

**Recommended OS:** Ubuntu 18.04 and up.

### Using Vagrant (recommended)

1. Clone the repository using `git clone`.
2. Open a terminal and `cd` to the project's root directory.
3. **If your host is running Windows:** open `boostrap.sh` and **comment** `npm install` and `npm run dev`. (Near the end of the file)
4. Run `vagrant up`. Vagrant will download a box, install it and provision it. This will take a few minutes. Wait for the operation to finish.
5. Your work environment is ready.
6. **If your host is running Windows:** open `boostrap.sh` and **uncomment** `npm install` and `npm run dev`. (Near the end of the file)
7. **If your host is running Windows:** run `npm install && npm run dev` from the host. (Use npm from the host from now on)

The provision should have installed and configured everything you need to start working. You can access your web server from your host machine via `http://127.0.0.1:4567`.

**Mysql root credentials:**

Username: `root`  
Password: `root`  
Database name: `elyssif`  
Testing database name: `elyssif_test`  
Charset: `utf8_general_ci`

**Mysql laravel credentials:**

Username: `laravel`  
Password: `secret`

**Virtual Machine credentials:**

Username: vagrant  
Password: vagrant

### Manually

1. Clone the repository using `git clone`.
2. Check if you meet all the requirements.
3. Create a new virtual host in you web server. Enable the `rewrite` mod if not already enabled.
4. Create a database with the `utf8_general_ci` charset.
5. Open a terminal and `cd` to the project's root directory.
6. Make sure the `www-data` user has write access to the `storage` and `bootstrap` directories.
7. Copy `env.example` and change its content to match your local configuration.
8. Configure your web server by creating a new virtual host for the project. The document root must be the project's root.
9. Run the following commands (replacing the path with your path):
```
composer install

php artisan key:generate

php artisan migrate:install
php artisan migrate
php artisan db:seed

php artisan passport:install

supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker:*

line="* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1"
(crontab -u www-data -l; echo "$line" ) | crontab -u www-data -

npm install
npm run dev
```

## Running the tests

To run the automated tests, simply run : `php ./vendor/phpunit/phpunit/phpunit` when your current directory is the root of the project.
