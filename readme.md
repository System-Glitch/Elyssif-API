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
* xdebug
* Redis
* Bitcoin Core (bitcoind) >= 0.18.0
* Python3 (Optional)
* [VirtualBox](https://www.virtualbox.org/) and [Vagrant](https://www.vagrantup.com/) (Optional but recommended)
* [Postman](https://www.getpostman.com/) (Optional but recommended)

**Recommended OS:** Ubuntu 18.04 and up.

### Using Vagrant (recommended)

1. Clone the repository using `git clone`.
2. Open a terminal and `cd` to the project's root directory.
3. Run `vagrant up`. Vagrant will download a box, install it and provision it. This will take a few minutes. Wait for the operation to finish.
4. Your work environment is ready.

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
9. Create the following files:
- /etc/supervisor/conf.d/laravel-worker.conf
```
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /vagrant/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/vagrant/storage/logs/worker.log
```
- /etc/supervisor/conf.d/laravel-echo.conf
```
[program:laravel-echo]
directory=/var/www
process_name=%(program_name)s_%(process_num)02d
command=laravel-echo-server start
autostart=true
autorestart=true
user=vagrant
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/storage/logs/echo.log
```
10. Configure /etc/bitcoin/bitcoin.conf with the following entries:
```
regtest=1
bind=127.0.0.1:18445
walletnotify=php /path/to/project/artisan bitcoin:transaction %s
blocknotify=php /path/to/project/artisan bitcoin:confirmations
```
11. Run the following commands (replacing the path with your path):
```
composer install

php artisan key:generate

php artisan migrate:install
php artisan migrate
php artisan db:seed

php artisan passport:install

cp laravel-echo-server.json.example laravel-echo-server.json

# Generate rpc auth and append them to your .env file
python3 /path/to/project/rpcauth.py laravel /etc/bitcoin/bitcoin.conf /path/to/project/.env

supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker:*
supervisorctl start laravel-echo:*

service bitcoind start
# If you want to run bitcoind on startup
# systemctl enable bitcoind

line="* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1"
(crontab -u www-data -l; echo "$line" ) | crontab -u www-data -
```

## Running the tests

To run the automated tests, simply run : `php ./vendor/phpunit/phpunit/phpunit` when your current directory is the root of the project. A code coverage report will be generated in the `report` folder.
