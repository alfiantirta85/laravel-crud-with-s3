# Data Murid CRUD with Laravel and S3

## Requirement

1. MySQL Database
2. S3 Object Storage

## Deployment with Apache2

1. Install LAMP Stack

```
sudo apt install apache2 zip unzip libzip-dev php php-fpm php-mysql php-curl php-gd php-intl php-xsl php-mbstring
```

2. Enable Apache2

```
sudo systemctl start apache2
sudo systemctl enable apache2
sudo systemctl status apache2
```

3. Clone Source Code

```
git clone https://github.com/alfiantirta85/laravel-crud-with-s3.git
sudo cp -R laravel-crud-with-s3/* /var/www/html
sudo cp laravel-crud-with-s3/.* /var/www/html
cd /var/www/html
sudo rm index.html
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R a+rw storage
sudo chmod -R g+s storage
```

4. Install Composer

```
sudo apt install composer
composer --version
```

5. Install Dependencies

```
composer install
composer require league/flysystem-aws-s3-v3
```

6. Setup Environment File

```
cp .env.example .env
vi .env
---
DB_CONNECTION=mysql
DB_HOST=YOUR-DB-HOST
DB_PORT=3306
DB_DATABASE=YOUR-DB-NAME
DB_USERNAME=YOUR-DB-USER
DB_PASSWORD=YOUR-DB-PASSWORD

FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=YOUR-ACCESS-KEY
AWS_SECRET_ACCESS_KEY=YOUR-SECRET-KEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=YOUR-BUCKET
AWS_USE_PATH_STYLE_ENDPOINT=false
---
```

7. Generate App Key

```
php artisan key:generate
```

8. Database Migration

```
php artisan migrate
```

9. Configure Apache2

```
sudo vi /etc/apache2/sites-available/000-default.conf
---
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/public

        <directory /var/www/html/public>
                Options Indexes MultiViews FollowSymLinks
                AllowOverride All
                Require all granted
        </directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
---

apachectl configtest
sudo systemctl restart apache2
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## Access Web

```
http://YOUR-IP/posts
```
