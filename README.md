# Redirect
----------

Simple redirect utility intended to be used in conjunction with DNS records to allow pattern based redirects for apex records or more complex redirect logic.

## Installation

To install just clone the repository and run the migrations/seeds.

### Clone the repository

```sh
cd /var/www/vhosts
git clone git@github.com:journeygroup/redirect.git
cd redirect
```

### Configure

Configure the redirect tool by copying the default configurations and editing them:

```sh
cp phinx.example.yml phinx.yml
cp config/auth.example.php config/auth.php
cp config/database.example.php config/database.php
```

Edit newly created files above with database and authentication credentials.

### Initialize the database

```
composer install
php vendor/bin/phinx migrate
php vendor/bin/phinx seed:run
```

### Login

To login and edit your redirects you must enter through the admin panel (otherwise you'll be redirected): `xx.xx.xx.xx/admin`

## Apache Setup

On a given server where this should be installed, you need the public directory of this project to be the default virtual host. Here is an example of a default virtual host:

```
# file: /etc/httpd/vhosts/000-default.conf
<VirtualHost *:80>
    DocumentRoot "/var/www/vhosts/redirect/public"
    ServerName default
    ErrorLog "/var/log/httpd/redirect-errors.log"
    CustomLog "/var/log/httpd/redirect-access.log" common
</VirtualHost>
```

_Note: Apache virtual hosts are resolved in alphabetical order until a match is found. If no match is found, then the first virtual host is used. Prefixing the configuration with 000 ensures this is the file that resolves._


