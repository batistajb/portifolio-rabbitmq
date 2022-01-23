#!/bin/sh

cd /var/www
ln -sf .env.docker .env
composer install -o


DATABASES='laravel-api'

while ! nc -i 4 -z database 3306 ;
do
  echo "+ Database Connection is not up yet..."
done
echo "+ Database connection is up!"

for db in ${DATABASES};
do
  echo "+ Creating database ${db}"
  mysql -uroot \
        -proot \
        -hdatabase \
        -e "CREATE DATABASE IF NOT EXISTS \`${db}\`;"
done

echo "+ All OK"

chmod -R 777 *

nginx start
