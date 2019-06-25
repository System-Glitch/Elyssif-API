separator()
{
	echo "-------------------------"
}

prepare()
{
	cd /vagrant

	apt-get install software-properties-common
	apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
	add-apt-repository 'deb [arch=amd64,arm64,ppc64el] http://mariadb.mirrors.ovh.net/MariaDB/repo/10.3/ubuntu bionic main'
	add-apt-repository ppa:bitcoin/bitcoin
	apt-get update

	debconf-set-selections <<< 'maria-db-10.3 mysql-server/root_password password root'
	debconf-set-selections <<< 'maria-db-10.3 mysql-server/root_password_again password root'
}

install_dependencies()
{
	separator
	echo "Installing dependencies..."

	apt-get install -y apache2 apache2-utils libexpat1 ssl-cert
	apt-get install -y php7.2 libapache2-mod-php7.2 php7.2-curl php7.2-mysql php7.2-json php7.2-gd php7.2-intl php7.2-gmp php7.2-mbstring php7.2-xml php7.2-zip php7.2-bcmath php-xdebug
	apt-get install -y mariadb-server mariadb-client
	apt-get install -y redis-server redis-tools
	apt-get install -y composer npm
	apt-get install -y git
	apt-get install -y supervisor
	apt-get install -y python3 bitcoind

	apt-get -y autoremove

	npm install --global cross-env
	npm install --global laravel-echo-server

	composer install
}

setup_symlink()
{
	echo "Creating symlink"
	if ! [ -L /var/www ]; then
		rm -rf /var/www
		ln -fs /vagrant /var/www
	fi
}

setup_database()
{
	separator
	echo "Setting up database..."
	mysql -u root --password="root" --execute="CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'secret'"
	mysql -u root --password="root" --execute="CREATE DATABASE elyssif DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci"
	mysql -u root --password="root" --execute="GRANT ALL PRIVILEGES ON elyssif.* TO 'laravel'@'localhost'"
	mysql -u root --password="root" --execute="CREATE DATABASE elyssif_test DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci"
	mysql -u root --password="root" --execute="GRANT ALL PRIVILEGES ON elyssif_test.* TO 'laravel'@'localhost'"

	php artisan migrate:install
	php artisan migrate
	php artisan db:seed
}

setup_bitcoin()
{
	echo "Setting up bitcoind..."
	cat <<EOF > /etc/bitcoin/bitcoin.conf
regtest=1
bind=127.0.0.1:18445
walletnotify=php /vagrant/artisan bitcoin:transaction %s
blocknotify=php /vagrant/artisan bitcoin:confirmations
EOF

	echo "Generate RPC auth"
	python3 /vagrant/rpcauth.py laravel /etc/bitcoin/bitcoin.conf /vagrant/.env
	service bitcoind start
	systemctl enable bitcoind
}

setup_worker()
{
	echo "Setting up worker"
	cat <<EOF > /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /vagrant/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/vagrant/storage/logs/worker.log
EOF

}

setup_socket_io_server()
{
	echo "Setting up socket.io worker"
	cp /vagrant/laravel-echo-server.json.example /vagrant/laravel-echo-server.json
	cat <<EOF > /etc/supervisor/conf.d/laravel-echo.conf
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
EOF
}

configure_apache()
{
	separator
	echo "Configuring apache..."

	sed ':a;$!{N;ba};s/AllowOverride None/AllowOverride All/3' /etc/apache2/apache2.conf > apache2.conf
	mv apache2.conf /etc/apache2/apache2.conf

	setup_symlink
	echo "Creating virtual host"
	cat <<EOF > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/public

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF
}

configure()
{
	separator
	echo "Configuring..."

	configure_apache
	setup_worker
	setup_socket_io_server
	chmod -R 777 /vagrant/storage/
	chmod -R 777 /vagrant/bootstrap/

	cat <<EOF > /vagrant/.env
APP_NAME=Elyssif
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:4567

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elyssif
DB_USERNAME=laravel
DB_PASSWORD=secret

BROADCAST_DRIVER=redis
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=

ECHO_HOST=http://127.0.0.1:6001
ECHO_APP=elyssif
ECHO_KEY=818c8c8c73e1c81e1fe20b4eba4f01c7

MIN_CONFIRMATIONS=3
BITCOIN_FEES=0.0000332
ELYSSIF_FEES=0.0004
MIN_SELLER_PROFIT=0.0004
BITCOIND_HOST=localhost:18443
EOF

	setup_bitcoin

	chmod 777 .env
	php artisan key:generate

	setup_database
	php artisan passport:install

	register_cron_task
}

install()
{
	install_dependencies
	configure
}

start_worker()
{
	separator
	echo "Starting worker..."

	supervisorctl reread
	supervisorctl update
	supervisorctl start laravel-worker:*
	supervisorctl start laravel-echo:*
}

register_cron_task()
{
	separator
	echo "Registering cron task"

	line="* * * * * php /vagrant/artisan schedule:run >> /dev/null 2>&1"
	(crontab -u www-data -l; echo "$line" ) | crontab -u www-data -
}

provision()
{
	echo "Starting provisionning..."
	prepare
	install

	separator

	a2enmod rewrite
	service apache2 restart
	start_worker
	echo "Provision completed"
}

provision
